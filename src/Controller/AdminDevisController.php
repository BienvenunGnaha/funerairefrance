<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Devis;
use App\Entity\Text;
use App\Entity\Color;
use App\Entity\Motif;
use App\Entity\Granit;
use App\Entity\Gallery;
use App\Entity\Stele;
use App\Entity\Config;
use App\Entity\EmailTemplate;
use App\Entity\LastMessage;
use App\Entity\Message;
use App\Services\SendMail;
use App\Services\CartService;
use App\Services\DevisService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use App\Entity\Cart;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminDevisController extends AbstractController
{
    /**
     * @Route("/admin/devis", name="voir_le_devis")
     */
        
        public function index(Request $request, SendMail $mailer, CartService $cartService, DevisService $devisService, $projectDir)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Devis::class);
        $id = $request->query->get('id');
        $devis = $repository->find($id);
        $texts = $em->getRepository(Text::class)->findAll();
        $colors = $em->getRepository(Color::class)->findAll();
        $motifs = $em->getRepository(Motif::class)->findAll();
        $devis_details = $cartService->devis($devis, $devis->getUser(), $em);
        dump($devis->getUser());
        $token = $request->request->get('_token');
        if(is_string($token) && $this->isCsrfTokenValid('frfu-update-devis-token', $token)){
            $granit = $em->getRepository(Granit::class)->find((int)$request->request->get('granit'));
            $gallery = $em->getRepository(Gallery::class)->find((int)$request->request->get('gallery'));
            $photo = $request->request->get('photo');
            $svg = $request->request->get('svg');
            $total = (int)$request->request->get('total');
            $priceText = $request->request->get('priceText');
            $priceImage = $request->request->get('priceImage');
            $metaData = $request->request->get('metaData');
            dump($priceText);
            dump($priceImage);

            $pathSvg = $projectDir."/public".str_replace('https://funerairefrance.com', '',$devis->getSvg());

            if(file_exists($pathSvg)){ 
                unlink($pathSvg);

            }

            $directory = '/devis';
            $publicDirectory = '../public/devis';
            $filenamesvg = $directory.'/devis_funerairefrance_preview_'.$devis->getId().'.png';
            $svgFilepath =  $publicDirectory .'/devis_funerairefrance_'.$devis->getId().'.png';
            $base64_string = str_replace('data:image/png;base64,', '', $svg);
            $base64_string = str_replace(' ', '+', $base64_string);

            $decoded = base64_decode($base64_string);
            file_put_contents($svgFilepath,$decoded);
            $devis->setSvg('https://funerairefrance.com'.$filenamesvg);
            

            
            //die();
            $devis->setPhoto($photo)
                ->setTotal($total)
                ->setGranit($granit)
                ->setGallery($gallery)
                ->setPriceText((int)$priceText)
                ->setPriceImage((int)$priceImage)
                ->setMetaData($metaData);
            
            
            $em->flush($devis);



            $devis_details = $cartService->devis($devis, $devis->getUser(), $em);
            if($devis->getUser()){
                if($devis->getUser()->getType()){
                    if($devis->getUser()->getType()->getId() == 2){
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
            }else{
                    $devis->setTotal($devis_details['total']);
                    $devis->setTotalTtc($devis_details['total']);
            }
            $em->flush($devis);

            $this->generatePdfAndSend($devis, $devisService, $devis_details, $em, $mailer);

            
            //return $this->json(['success' => true], 200);
        }

    
        return $this->render('admin_devis/index.html.twig', [
            'devis' => $devis, 'texts' => $texts, 'colors' => $colors, 'motifs' => $motifs, 'devis_details' => $devis_details
        ]);
    }

    /**
     * @Route(path = "/admin/cart", name = "voir_la_commande")
     */
    public function showCartWithPreview(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Cart::class);
        $id = $request->query->get('id');
        $entity = $repository->find($id);

        ///dump($entity);

        if($entity instanceof Cart){
            return $this->render('admin_dashboard/cart-view.html.twig', [
                //'users' => $users,
                'cart' => $entity
            ]);
        }

        
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route(path = "/admin/devis/{id}/download/images", name = "admin_devis_download_images")
     */
    public function adminDevisDownloadImages(Request $request, CartService $cartService,$id, $projectDir)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Devis::class);
        $entity = $repository->find($id);

        if($entity instanceof Devis){
            $lists = $cartService->devisListImages($entity);
            //dd($lists);
            $zip = new \ZipArchive;
            $filename = $projectDir.'/public/downloads/devis/FuneraireFrance_devis_'.$entity->getId().'.zip';

            if(file_exists($filename)){
                unlink($filename);
            }

            if ($zip->open($filename, \ZipArchive::CREATE) === TRUE)
            {
                for($i = 0; $i < count($lists); $i++){
                    $zip->addFile($lists[$i], basename($lists[$i]));
                }
                
                $zip->close();
            }
            
            $stream  = new Stream($filename); 
            $response = new BinaryFileResponse($stream);

            return $response;
        }

        
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route(path = "/admin/cart/download/images", name = "Telecharger_les_images")
     */
    public function adminCartDownloadImages(Request $request, CartService $cartService, $projectDir)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Cart::class);
        $id = $request->query->get('id');
        $entity = $repository->find($id);

        if($entity instanceof Cart){
            $lists = $cartService->cartListImages($entity);
            //dd($lists);
            $zip = new \ZipArchive;
            $filename = $projectDir.'/public/downloads/orders/FuneraireFrance_orders_'.$entity->getId().'.zip';

            if(file_exists($filename)){
                unlink($filename);
            }

            if ($zip->open($filename, \ZipArchive::CREATE) === TRUE)
            {
                for($i = 0; $i < count($lists); $i++){
                    $zip->addFile($lists[$i], basename($lists[$i]));
                }
                
                $zip->close();
                $stream  = new Stream($filename); 
                $response = new BinaryFileResponse($stream);

                return $response;
            }
            
            
        }

        
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin/devis/{id}/send/message", name="admin-devis-send-msg")
     */

    public function sendMsg(Request $request, $id, SendMail $mailer, DevisService $devisService, CartService $cartService){
        $em = $this->getDoctrine()->getManager();
        $devis = $em->getRepository(Devis::class)->find($id);
        try{
            if(!$devis->getUser()){
                throw new \Exception('Aucun utilisateur n\'est associé à ce devis.');
            }

            $devis_details = $cartService->devis($devis, $devis->getUser(), $em);
            $this->generatePdfAndSend($devis, $devisService, $devis_details, $em, $mailer);
            $message = new Message();
            $message->setContent('Bonjour '.$devis->getUser()->getFirstName().',  Votre devis est en effet pret et téléchargeable sur le lien ci-dessous');
            $message->setIsCustomer(false);
            $message->setCreatedAt(new \DateTime());
            $message->setLink($devis->getDownload());
            $message->setLabelLink('Télécharger le devis');
            $message->setSender($this->getUser());
            $message->setReceiver($devis->getUser());
            $message->setDevis($devis);
            
        
            
            $em->persist($message);
            $em->flush();

            $contentMail = $this->getDoctrine()->getManager()->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'new_message']);
            $data = [
                'to' => $devis->getEmail() ? $devis->getEmail() : $this->getUser()->getEmail(),
                'body' => $contentMail->getContent(),
                'subject' => $contentMail->getName()
            ];
            $mailer->send($data);
            $user = $this->getUser();
            $receiver = $message->getReceiver();
            $lm = $em->getRepository(LastMessage::class)->findOneBy(['user' => $user->getId()]);
            $lmRcv = $em->getRepository(LastMessage::class)->findOneBy(['user' => $receiver->getId()]);
            if($lm){
                $lm->setMessage($message);
                $lm->setLastAt(new \DateTime());
                $lm->setCount($lm->getCount()+1);
                $em->flush($lm);
            }else{
                $lm = new LastMessage();
                $lm->setUser($user)->setMessage($message)->setLastAt(new \DateTime());
                $lm->setCount(1);
                $em->persist($lm);
                $em->flush();
            }

            if($lmRcv){
                $lmRcv->setMessage($message);
                $lmRcv->setLastAt(new \DateTime());
                $lmRcv->setCount($lmRcv->getCount()+1);
                $em->flush($lmRcv);
            }else{
                $lmRcv = new LastMessage();
                $lmRcv->setUser($receiver)->setMessage($message)->setLastAt(new \DateTime());
                $lmRcv->setCount(1);
                $em->persist($lmRcv);
                $em->flush();
            }
           // throw new Exception();
            $this->addFlash(
                'success',
                'Devis envoyé au client avec success!'
            );
        }catch(\Exception $e){
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        
        

        return $this->redirect($request->headers->get('referer'));
    }

    private function generatePdfAndSend(Devis $devis, DevisService $devisService, $devis_details, $em, SendMail $mailer, $mailing = true)
    {
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
        $directory = '/devis';
        $publicDirectory = '../public/devis';
        $filename = $directory.'/devis_funerairefrance_'.$devis->getId().'.pdf';
        $pdfFilepath =  $publicDirectory .'/devis_funerairefrance_'.$devis->getId().'.pdf';
        $first = $devis->getFirstName();
        $name = $devis->getName();
        
        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        if ($mailing === true) {
            
            $contentMail = $em->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'devis']);
        
        
            $content = str_replace([ '::name::', '::link::'], 
            [$first.' '.$name, 'https://funerairefrance.com'.$filename], $contentMail->getContent());
            $data = [
                'to' => $devis->getEmail() ? $devis->getEmail() : $this->getUser()->getEmail(),
                'body' => $content,
                'subject' => 'Devis Articles personnalisés'
            ];

            $mailer->sendWithAttachement($data, [$filename]);
        }
        
        $devis->setIsEnabled(true);
        $devis->setDownload($pdfFilepath);
        $em->flush($devis);
    }
}
