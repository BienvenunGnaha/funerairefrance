<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Text;
use App\Entity\Color;
use App\Entity\Order;
use App\Entity\Motif;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\UploadFileManager;
use App\Entity\Devis;
use App\Entity\Fixation;
use App\Entity\Component;
use App\Entity\User;
use App\Entity\MoreInfo;
use App\Entity\Config;
use App\Entity\Cart;
use App\Entity\Status;
use App\Entity\Rating;
use App\Entity\MotifGallery;
use App\Entity\EmailTemplate;
use App\Entity\Gallery;
use App\Form\RatingType;
use App\Form\DevisType;
use App\Form\OrderType;
use App\Form\DevisPaymentType;
use App\Form\MoreInfoType;
use App\Form\OrderCustomType;
use App\Services\SerializerService;
use App\Services\CartService;
use App\Services\DevisService;
use App\Services\SendMail;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\SetupIntent;
use Stripe\PaymentIntent;
use Stripe\Plan;
use Stripe\Exception\CardException;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
    

    /**
     * @Route("/product/devis/download/{id}", name="product-devis-download")
     */
    public function devisDownload($id){
        return $this->render('product/download-devis.html.twig', [
            'devis' => $this->getDoctrine()->getManager()->getRepository(Devis::class)->findOneBy(['id' => $id])
        ]);
    }

    /**
     * @Route("/user/product/rating/{id}", name="product-rating")
     */
    public function productRating(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime();
        $prod = $em->getRepository(Product::class)->findOneBy(['id' => $id]);
        $rating = new Rating();
        $rating->setCreatedAt($date);
        if($prod){
            $rating->setProduct($prod);
        }
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $rating->setStatus(0);
            $em->persist($rating);
            $em->flush();

            return $this->redirect($request->headers->get('referer'));
        }
        
        
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("user/share/product/{id}", name="user-share-product")
     */
    public function share(Request $request, $id, SendMail $mailer){
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->findOneBy(['id' => $id]);
        
        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('share-product-friend-token', $submittedToken)) {

            $metaData = json_decode($request->request->get('metaData'));
            $email = $request->request->get('email');
            $name_sender = $request->request->get('name');
            $message = $request->request->get('message');
            $count = count($metaData);
            $contentMail = $em->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'share_product']);
            $prodLink = $this->generateUrl('product-show', [
                'id' => $id,
            ]);
            for($i = 0; $i < $count; $i++){
                $content = str_replace([ '::name_dest::', '::product::', '::name_sender::', '::message::'], 
                [$metaData[$i]->name, '<a href="https://funerairefrance.com'.$prodLink.'">'.$prod->getName().'</a>', $name_sender, $message], $contentMail->getContent());
                $data = [
                    'to' => $metaData[$i]->email,
                    'body' => $content,
                    'subject' => $contentMail->getName().', '.$metaData[$i]->name
                ];

                $mailer->send($data);
                
            }
            $this->addFlash(
                'success',
                'Le lien du product a été partagé avec succès!'
            );
            return $this->redirectToRoute('product-show', ['id' => $id]);
        }
        
        return $this->render('product/share.html.twig', ['prod' => $prod]);
    }

    /**
     * @Route("/product/show/{id}", name="product-show")
     */
    public function show(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime();
        $prod = $em->getRepository(Product::class)->findOneBy(['id' => $id]);
        
        $rating = new Rating();
        $order = new Order();
        $order->setCreatedAt($date);
        $order->setIsCustom(false);
        $related_prods = [];
        if($prod){
            $related_prods = $em->getRepository(Product::class)->findRelated($id, $prod->getCatId()->getId());
            //$order->setProductId($prod);
            $rating->setProduct($prod);
        }
        $userRating = $this->getUser();
        $rating->setUser($userRating);
        $form = $this->createForm(OrderType::class, $order);
        $formRating = $this->createForm(RatingType::class, $rating); 
        $submittedToken = $request->request->get('_token');
        //dump();
        //die();
        $ts = $date->getTimestamp() + 77760000;
        $date->setTimestamp($ts);
        if($this->isCsrfTokenValid('add-to-basket', $submittedToken))
        {
            dump($request->request->all());
            //die();
            $order->setProductId($prod);
            $order->setQty($request->request->get('qty'))->setMetaData($request->request->get('metaData'));
            $em->persist($order);
            $em->flush();
            
            $cookie = $request->cookies->get('frfu_crt_id');
            $cartCookie = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $cookie]);
            $cart = new Cart();
            if($cookie !== null && $cartCookie !== null){
                if($this->getUser()){
                    $user = $this->getUser();
                    if($user instanceof User && $cartCookie->getUser() === null){
                        $cartCookie->setUser($user);
                    }
                }
                $order->setCart($cartCookie);
                $em->flush();
                setcookie('frfu_crt_id', $cartCookie->getUniqId(), time() + 7776000, '/', 'funerairefrance.com', true, true);
                return $this->redirectToRoute('cart');
            }else{
                $cart->setUniqId(uniqid().'_'.(new \DateTime())->getTimestamp());
                $cart->addOrder($order);
                if($this->getUser()){
                    $user = $this->getUser();
                    if($user instanceof User){
                        $cart->setUser($user);
                    }
                }
                $status = $em->getRepository(Status::class)->find(1);
                $cart->setPaid(0);
                $cart->setStatus($status);
                $em->persist($cart);
                $em->flush();
                
                
                setcookie('frfu_crt_id', $cart->getUniqId(), time() + 7776000, '/', 'funerairefrance.com', true, true);
                return $this->redirectToRoute('cart');
                
            }
        }

        $res = new Response();
        $cookie = $request->cookies->get('frfu_crt_id');
        if($cookie === null){
            //$cookie = new Cookie('frfu_crt_id', '', $date, '/', 'funerairefrance.com', true, true);
            //$res->headers->setCookie( $cookie );
            setcookie('frfu_crt_id', 'ff', time() + 7776000, '/', 'funerairefrance.com', true, true);
        }


        
        return $this->render('product/show.html.twig', [
            'prod' => $prod, 'form' => $form->createView(), 'formRating' => $formRating->createView(),
            'ratings' => $em->getRepository(Rating::class)->findBy(['status' => 1, 'product' => $id ]), 
            'related_prods' => $related_prods,
        ]);
        
        //return ;
    }

    /**
     * @Route("/product/customize/{id}", name="product-customize")
     */
    public function customize(Request $request, $id, SendMail $mailer, CartService $cartService, $projectDir, DevisService $devisService)
    {
        //dump($projectDir);
        $devis = new Devis();
        $moreInfo = new MoreInfo();
        $devis->setCreatedAt(new \DateTime());
        if($this->getUser()){
            $devis->setUser($this->getUser());
        }
        
        $form = $this->createForm(DevisType::class, $devis);
        $formMore = $this->createForm(MoreInfoType::class, $moreInfo);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->find($id);
        $galleries= $em->getRepository(Gallery::class)->findBy(['product' => $id]);
        $texts = $em->getRepository(Text::class)->findAll();
        $colors = $em->getRepository(Color::class)->findAll();
        $motifs = $em->getRepository(Motif::class)->findAll();

        if($form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();

            if($this->getUser()){
                if($this->getUser()->getType()){
                    if($this->getUser()->getType()->getId() == 2){
                        $tvaConf = $em->getRepository(Config::class)->findOneBy(['confKey' => 'tva']);
                        $tvaProd = $devis->getProductId();
                        if($tvaProd){
                            if($tvaProd->getTva()){
                                $tva = $tvaProd->getTva()->getValue();
                                $devis->setTotalTtc($devis->getTotal() + ($devis->getTotal()*floatval($tva)));
                            }elseif($tvaConf){
                                $devis->setTotalTtc($devis->getTotal() + ($devis->getTotal()*floatval($tvaConf->getValue())));
                            }
                        }
                        elseif($tvaConf){
                            $devis->setTotalTtc($devis->getTotal() + ($devis->getTotal()*floatval($tvaConf->getValue())));
                        }
                    }
                }
            }

            $directory = '/devis';
            $publicDirectory = '../public/devis';

            $devis->setIsEnabled(false);
            $devis->setFirstFeePayed(false);
            $devis->setSecondFeePayed(false);
            $devis->setStatus($em->getRepository(Status::class)->find(1));
           
            $em->persist($devis);
            $em->flush();
            $filenamesvg = $directory.'/devis_funerairefrance_'.$devis->getId().'.png';
            $svgFilepath =  $publicDirectory .'/devis_funerairefrance_'.$devis->getId().'.png';
            $base64_string = str_replace('data:image/png;base64,', '', $request->request->all()['devis']['svg']);
            $base64_string = str_replace(' ', '+', $base64_string);
            $decoded = base64_decode($base64_string);
            file_put_contents($svgFilepath,$decoded);
            $devis->setSvg('https://funerairefrance.com'.$filenamesvg);
            
            $devis_details = $cartService->devis($devis, $this->getUser(), $em);

            if($this->getUser()){
                if($this->getUser()->getType()){
                    if($this->getUser()->getType()->getId() == 2){
                        $devis->setTotal($devis_details['totalHT']);
                        $devis->setTotalTtc($devis_details['total']);
                    }else{
                        $devis->setTotal($devis_details['total']);
                        $devis->setTotalTtc($devis_details['total']);
                    }
                }else{
                    $devis->setTotal($devis_details['total']);
                    $devis->setTotalTtc($devis_details['total']);
                }
            }
            else{
                $devis->setTotal($devis_details['total']);
                $devis->setTotalTtc($devis_details['total']);
            }
            $em->flush($devis);

            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            $pdfOptions->set('isRemoteEnabled', true);
            
            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);
            
            
            
            // Load HTML to Dompdf
            $dompdf->loadHtml($devisService->generateHtml($devis, $devis_details, $em));
            
            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Store PDF Binary Data
            $output = $dompdf->output();
            
            // In this case, we want to write the file in the public directory
           
            $filename = $directory.'/devis_funerairefrance_'.$devis->getId().'.pdf';
            $pdfFilepath =  $publicDirectory .'/devis_funerairefrance_'.$devis->getId().'.pdf';
            $first = $devis->getFirstName();
            $name = $devis->getName();
            
            // Write file to the desired path
            file_put_contents($pdfFilepath, $output);

            $contentMail = $this->getDoctrine()->getManager()->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'devis']);
            
            
            $content = str_replace([ '::name::', '::link::'], 
            [$first.' '.$name, 'https://funerairefrance.com'.$filename], $contentMail->getContent());
            $data = [
                'to' => $devis->getEmail() ? $devis->getEmail() : $this->getUser()->getEmail(),
                'body' => $content,
                'subject' => 'Devis Articles personnalisés'
            ];

            $mailer->sendWithAttachement($data, [$filename]);
            $devis->setIsEnabled(true);
            $devis->setDownload($pdfFilepath);
            $em->flush($devis);
            $configFromEmail = $em->getrepository(Config::class)->findOneBy(['confKey' => 'from_email_contact']);
            $mailNewDevis = $em->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'nouveau_devis']);
            //try{
                $mailer->sendWithAttachement([
                    'to' => $configFromEmail->getValue(),
                    'body' => $mailNewDevis->getContent(),
                    'subject' => $mailNewDevis->getName()
                ], [$filename]);
            //}catch(\Exception $e){

            //}
            
            /*$serializerService = new SerializerService();
            $serializer = $serializerService->getSerializer();
            $devisDecoded = json_decode($serializer->serialize($devis, 'json'), true);*/

            //return $this->file('/www/france-funeraire/public'.$filename);

            return $this->json(['success' => true, 'devis' => $devis->getId(), 'all' => explode("public", $pdfFilepath)[1]]);
        }

        dump($prod->getGalleries());

        return $this->render('product/customize.html.twig', [
            'prod' => $prod, 'texts' => $texts, 'colors' => $colors, 'motifs' => $motifs,
             'formDevis' => $form->createView(), 'formMoreInfo' => $formMore->createView(), 'galleries' => $galleries
        ]);
    }

    /**
     * @Route("/product/devis/contact/{id}", name="product-devis-contact")
     */
    public function devisProductContact(Request $request, $id, SendMail $mailer, CartService $cartService)
    {
        
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->find($id);
        $submittedToken = $request->request->get('_token');
        if($this->isCsrfTokenValid('contact-produit', $submittedToken)){

            $em = $this->getDoctrine()->getManager();
            $name = $request->request->get('name');
            $firstName = $request->request->get('firstName');
            $email = $request->request->get('email');
            $phone = $request->request->get('phone');
            $zip = $request->request->get('zip');
            $configFromEmail = $em->getrepository(Config::class)->findOneBy(['confKey' => 'from_email_contact']);
            $mailNewDevis = $em->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'devis_conract_conseiller']);
            $content = str_replace([ '::product::', '::name::', '::firstName::', '::email::', '::phone::', '::zip::', '::id::'], 
            [
                '<a href="https://funerairefrance.com'.$this->generateUrl('product-devis-contact', ['id' => $id]).'">'.$prod->getName().'</a>',
                $name, $firstName, $email, $phone, $zip, $id
            ], $mailNewDevis->getContent());
           

            $mailer->send([
                'to' => $configFromEmail->getValue(),
                'body' => $content,
                'subject' => $mailNewDevis->getName()
            ]);
            $this->addFlash(
                'success',
                'Votre demande a été envoyé .Un conseiller vous contactera par e-mail ou par téléphone.'
            );

            return $this->redirect($request->headers->get('referer'));
        }


        return $this->render('product/contact.html.twig', [
            'prod' => $prod
        ]);
    }

    /**
     * @Route("/product/order/customize-before/{id}", name="product-before-customize-before")
     */
    public function customizeBeforeOrder(Request $request, $id, SendMail $mailer)
    {
        
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->find($id);
        $order = new Order();
        $order->setIsCustom(true);
        $order->setQty(1);
        $order->setProductId($prod); 
        if($this->getUser()){
            $order->setUserId($this->getUser());
        }
        
        $form = $this->createForm(OrderCustomType::class, $order);
        $form->handleRequest($request);

        if($form->isSubmitted() ){
            

            $em->persist($order);
            $em->flush();
            
            $cookie = $request->cookies->get('frfu_crt_id');
            $cartCookie = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $cookie]);
            $cart = new Cart();
            if($cookie !== null && $cartCookie !== null){
                if($this->getUser()){
                    $user = $this->getUser();
                    if($user instanceof User && $cartCookie->getUser() === null){
                        $cartCookie->setUser($user);
                    }
                }
                $order->setCart($cartCookie);
                if($order->getInvoice() !== null){   
                    $directory = '/devis';
                    $publicDirectory = '../public/devis';
                    $filenamesvg = $directory.'/order_preview_funerairefrance_'.$order->getId().'.png';
                    $svgFilepath =  $publicDirectory .'/order_preview_funerairefrance_'.$order->getId().'.png';
                    $base64_string = str_replace('data:image/png;base64,', '', $request->request->all()['order_custom']['invoice']);
                    $base64_string = str_replace(' ', '+', $base64_string);
                    $decoded = base64_decode($base64_string);
                    file_put_contents($svgFilepath,$decoded);
                    $order->setInvoice('https://funerairefrance.com'.$filenamesvg);
                    $em->flush();
                }
                setcookie('frfu_crt_id', $cartCookie->getUniqId(), time() + 7776000, '/', 'funerairefrance.com', true, true);
                return $this->redirectToRoute('cart');
            }else{
                $cart->setUniqId(uniqid().'_'.(new \DateTime())->getTimestamp());
                $cart->addOrder($order);
                if($this->getUser()){
                    $user = $this->getUser();
                    if($user instanceof User){
                        $cart->setUser($user);
                    }
                }
                $status = $em->getRepository(Status::class)->find(1);
                $cart->setPaid(0);
                $cart->setStatus($status);
                $em->persist($cart);
                $em->flush();
                
                setcookie('frfu_crt_id', $cart->getUniqId(), time() + 7776000, '/', 'funerairefrance.com', true, true);
                return $this->redirectToRoute('cart');
                
            }

        }

        $texts = $em->getRepository(Text::class)->findAll();
        $colors = $em->getRepository(Color::class)->findAll();
        $motifs = $em->getRepository(Motif::class)->findAll();
        $fixations = $em->getRepository(Gallery::class)->findByFixation($prod);
        $granits = $em->getRepository(Gallery::class)->findByGranit($prod);
        $components = $prod->getComponents();

        //dump($prod);
        return $this->render('product/customize-order.html.twig', [
            'prod' => $prod, 'texts' => $texts, 'colors' => $colors, 'motifs' => $motifs, 'form' => $form->createView(),
            'fixations' => $fixations, 'granits' => $granits, 'components' => $components
            ]);
    }

    /**
     * @Route("/fixation/all", name="fixations-all")
     */
    public function fixations(){
        $em = $this->getDoctrine()->getManager();
        $fixations = $em->getRepository(Fixation::class)->findAll();
        $count = count($fixations);
        $data = [];
        for($i = 0; $i < $count; $i++){
            $data[] = ['id' => $fixations[$i]->getId(), 'name' => $fixations[$i]->getName(), 'photo' => $fixations[$i]->getPhoto()];
        }
        return $this->json($data, 200);
    }

    /**
     * @Route("/component/all", name="components-all")
     */
    public function components(){
        $em = $this->getDoctrine()->getManager();
        $components = $em->getRepository(Component::class)->findAll();
        $count = count($components);
        $data = [];
        for($i = 0; $i < $count; $i++){
            $data[] = ['id' => $components[$i]->getId(), 'name' => $components[$i]->getName(),];
        }
        return $this->json($data, 200);
    }

    /**
     * @Route("/motif/{id}/gallery", name="motif-gallery")
     */
    public function motifsGallery($id){
        $em = $this->getDoctrine()->getManager();
        $motifs = $em->getRepository(MotifGallery::class)->findBy(['motif' => $id]); 
        $serializerService = new SerializerService();
        $serializer = $serializerService->getSerializer();
        $json_motifs = json_decode($serializer->serialize($motifs, 'json'), true);
        $isPro = false;
        $user = $this->getUser();
        if($user){
            if($user->getType()){
                if($user->getType()->getId() == 2){
                    $isPro = true;
                }
            }
        }
        return $this->json(['success' => true, 'motifs' => $json_motifs, 'ispro' => true], 200);
    }

    /**
     * @Route("/motif/search/{search}", name="motif-search-gallery")
     */
    public function motifsSearchGallery($search){
        $em = $this->getDoctrine()->getManager();
        $motifs = $em->getRepository(MotifGallery::class)->search($search);
        $serializerService = new SerializerService();
        $serializer = $serializerService->getSerializer();
        $json_motifs = json_decode($serializer->serialize($motifs, 'json'), true);
        $isPro = false;
        $user = $this->getUser();
        if($user){
            if($user->getType()){
                if($user->getType()->getId() == 2){
                    $isPro = true;
                }
            }
        }
        return $this->json(['success' => true, 'motifs' => $json_motifs, 'ispro' => true], 200);
    }

    /**
     * @Route("/product/upload/custom-img", name="product-upload-custom-img")
     */
    public function uploadCustom(Request $request){
        $data = $request->files->get('custom-img');
        $module = 'custom';
        $folder = 'uploads/'. $module .'/new/original';
        $extension_allowed = array('png', 'jpeg', 'jpg', 'gif');
        $uploader = new UploadFileManager($module);
        $uploader->createDirectory();
        $uploaded = $uploader->uploadFile($data, $folder, 8000000, $extension_allowed);

        return $this->json(array('location' => $uploaded[0]->getPath().'/'.$uploaded[0]->getFileName()), 200);
    }


    /**
     * @Route("/user/devis/view/{id}", name="devis-view")
     */
    public function viewDevis(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $stripeSecret = $em->getRepository(Config::class)->findOneBy(['confKey' => 'stripe_sk']);
        $stripePublic = $em->getRepository(Config::class)->findOneBy(['confKey' => 'stripe_pk']);
        Stripe::setApiKey($stripeSecret->getValue());
        if(!$user->getStripeCustomer()){
            $cus = Customer::create([
                'name' => $user->getFirstName().' '.$user->getName(),
                'email' => $user->getEmail()
            ]);
            $user->setStripeCustomer($cus->id);
            $em->flush($user);
        }
        $devis = $em->getRepository(Devis::class)->find($id);
        $form = $this->createForm(DevisPaymentType::class, $devis);
        $setupIntent = SetupIntent::create([ 'customer' => $user->getStripeCustomer()]);
        $isPro = false;
        if($user->getType()){
            if($user->getType()->getId() === 2){
                $isPro = true;
            }
        }
        $form->handleRequest($request);
       // dump($request->request->all());
        if($form->isSubmitted() && $form->isValid()){
            
            $em->flush($devis);
            if($devis->getMethod()->getId() === 1){
                $price = $devis->getTotalTtc() * 0.3;
                if($devis->getFirstFeePayed() === true && $devis->getSecondFeePayed() === false){
                    $price = $devis->getTotalTtc() * 0.7;
                }
                $pi = PaymentIntent::create([
                    'amount' => $price*100,
                    'currency' => 'eur',
                    'customer' => $user->getStripeCustomer(),
                    'payment_method' => $devis->getPm(),
                    'off_session' => true,
                    'confirm' => true,
                ]);

                if($pi->status === "succeeded"){
                    if($devis->getFirstFeePayed() === true){
                        $devis->setSecondFeePayed(true);
                    }else{
                        $devis->setFirstFeePayed(true);
                        $devis->setStatus($em->getRepository(Status::class)->find(2));
                    }
                    
                    $em->flush($devis);
                    $this->addFlash(
                        'success',
                        'Le paiement  a été effectué avec succès. Votre commande est en traitement.'
                    );
                }
                else{
                    $this->addFlash(
                        'danger',
                        'Le paiement n\' a pas pu être effectué. Veuillez vérifiez vos informations de paiement ou essayez une autre carte de crédit'
                    );
                }
            }
            elseif($devis->getMethod()->getId() === 2){
                //die();
                $price = $devis->getTotalTtc()/10;
                $plan = Plan::create([
                    'amount' => $price*100,
                    'currency' => 'eur',
                    'interval' => 'month',
                    'product' => ['name' => 'Modalité de 10 mois '.$devis->getId()],
                ]);

                try {
                    Customer::update(
                      $user->getStripeCustomer(),
                      ['invoice_settings' => ['default_payment_method' => $devis->getPm()]]
                    );
               } catch (CardException $e) {
                     // Error code will be authentication_required if authentication is needed
                   return $this->json(array('unsuccess' => $e->getMessage), 500);
               }

                $devis->setPlan($plan->id);
                $devis->setStatus($em->getRepository(Status::class)->find(2));
                $em->flush($devis);

                $subscription = Subscription::create([
                    'customer' => $user->getStripeCustomer(),
                    'items' => [['plan' => $devis->getPlan()]],
                    'off_session' => TRUE
                ]);

                if($subscription->status == 'active'){
                    $devis->setRequiredUpdatePm(false);
                    $this->addFlash(
                        'success',
                        'Le paiement  a été effectué avec succès. Votre commande est en traitement.'
                    );
                }else{
                    $this->addFlash(
                        'danger',
                        'Le paiement n\' a pas pu être effectué. Veuillez vérifiez vos informations de paiement ou essayez une autre carte de crédit'
                    );
                }
                $devis->setSubscription($subscription->id);
                $devis->setCycle(10);
                $devis->setCycleTotal(10);
                $devis->setStatus($em->getRepository(Status::class)->find(2));
                $em->flush($devis);

            }
            elseif($devis->getMethod()->getId() === 3){
                $price = $isPro ? $devis->getTotalTtc()/24 : $devis->getTotal()/24;
                $plan = Plan::create([
                    'amount' => $price*100,
                    'currency' => 'eur',
                    'interval' => 'month',
                    'product' => ['name' => 'Modalité de 24 mois '.$devis->getId()],
                ]);

                try {
                    Customer::update(
                      $user->getStripeCustomer(),
                      ['invoice_settings' => ['default_payment_method' => $devis->getPm()]]
                    );
               } catch (CardException $e) {
                     // Error code will be authentication_required if authentication is needed
                   return $this->json(array('unsuccess' => $e->getMessage), 500);
               }

                $devis->setPlan($plan->id);
                $em->flush($devis);

                $subscription = Subscription::create([
                    'customer' => $user->getStripeCustomer(),
                    'items' => [['plan' => $devis->getPlan()]],
                    'off_session' => TRUE
                ]);
                if($subscription->status == 'active'){
                    $devis->setRequiredUpdatePm(false);
                    $this->addFlash(
                        'success',
                        'Le paiement  a été effectué avec succès. Votre commande est en traitement.'
                    );
                }else{
                    $this->addFlash(
                        'danger',
                        'Le paiement n\' a pas pu être effectué. Veuillez vérifiez vos informations de paiement ou essayez une autre carte de crédit'
                    );
                }
                $devis->setSubscription($subscription->id);
                $devis->setCycle(24);
                $devis->setCycleTotal(10);
                $devis->setStatus($em->getRepository(Status::class)->find(2));
                $em->flush($devis);
            }
            dump($devis);
            return $this->redirect('/user/devis');
        }
        
        return $this->render('devis/devis.html.twig', [
            'devis' => $devis, 'form' => $form->createView(), 'clientSecret' => $setupIntent->client_secret
        ]);
    }

    /**
     * @Route("product/more-info", name="product-more-info", methods={"POST"})
     */
    public function moreInfo(Request $request){
        $more = new MoreInfo();
        $more->setCallAt(new \DateTime());
        $form = $this->createForm(MoreInfoType::class, $more);
        //dump($request->request);
        //die();
        $form->handleRequest($request);
       $photo = $request->files->get('more_info')['photoConcession'];
       $acte = $request->files->get('more_info')['acteConcession'];
       $module = 'more-info';
        $folder = 'uploads/'. $module .'/new/original';
       if ($form->isSubmitted()) {
            dump($acte);
            dump($photo);
            
            if ($acte instanceof UploadedFile){
                $extension_allowed = array('png', 'jpeg', 'jpg', 'gif', 'pdf', 'doc', 'docx');
                $uploader = new UploadFileManager($module);
                $uploader->createDirectory();
                $acte_up = $uploader->uploadFile($acte, $folder, 8000000, $extension_allowed, uniqid().'_'.(new \DateTime())->getTimestamp().'_');
                $more->setActeConcession($acte_up[0]->getPath().'/'.$acte_up[0]->getFilename());
                
                dump($more);
            }
            
            
            if(is_array($photo) && count($photo) > 0){
                $extension_allowed = array('png', 'jpeg', 'jpg', 'gif');
                $uploader = new UploadFileManager($module);
                $uploader->createDirectory();
                $uploaded = $uploader->uploadFile($photo, $folder, 8000000, $extension_allowed, uniqid().'_'.(new \DateTime())->getTimestamp().'_', true);
                $more->setPhotoConcession($uploaded);
            }



        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($more);
        $entityManager->flush();

        return $more->getDevis() ? $this->redirect($this->generateUrl('product-devis-download', ['id' => $more->getDevis()->getId()])) : $this->redirect($request->headers->get('referer'));;
      }
        
        
        

        return $this->redirect($request->headers->get('referer'));
    }

}
