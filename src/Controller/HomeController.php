<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Product;
use App\Entity\Slider;
use App\Entity\Active;
use App\Entity\Category;
use App\Entity\Testimonial;
use App\Entity\Newletter;
use App\Entity\Guide;
use App\Entity\Faq;
use App\Entity\Config;
use App\Entity\User;
use App\Form\NewletterType;
use App\Form\GuideType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use App\Services\SendMail;
use App\Entity\EmailTemplate;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        

        /*$off = 0;
        $lim = 8;home_titre
        if($request->query->get('offset') && $request->query->get('limit') && (int)$request->query->get('limit') !== 0){
            $off = $request->query->get('offset');
            $lim = $request->query->get('limit');
        }*/
        $active = $em->getRepository(Active::class)->find(1);
        $sliders = [];
        if($active->getSlider()){
            $sliders = $em->getRepository(Slider::class)->findBy(['status' => true], ['id' => 'DESC'], 5, 0);
        }
        
        $cats = $em->getRepository(Category::class)->findBy([], ['displayOrder' => 'ASC'], 4, 0);
        $home_title = $em->getRepository(Page::class)->findOneBy(['slug' => 'home_titre']);
        $talk_about_us = $em->getRepository(Page::class)->findOneBy(['slug' => 'talk_about_us']);
        $talk_about_us_image = $em->getRepository(Page::class)->findOneBy(['slug' => 'talk_about_us_images']);
        $yt = $em->getRepository(Page::class)->findOneBy(['slug' => 'youtube']); 
        $yt_desc = $em->getRepository(Page::class)->findOneBy(['slug' => 'youtube_desc']); 
        $yt_desc2 = $em->getRepository(Page::class)->findOneBy(['slug' => 'youtube_desc2']); 
        $etape_desc = $em->getRepository(Page::class)->findOneBy(['slug' => 'etape_desc']);
        $testimonials = $em->getRepository(Testimonial::class)->findBy([], ['id' => 'DESC'], 10, 0); 
        //dump($talk_about_us);
        //die();
        /*$length = count($prods);
        $diviser = 1;
            if($length >= 4){
            $diviser = 2;
            }
        $entries  = intdiv($length, $diviser);
        $products = [];

        for($i = 0; $i < $diviser; $i++){
            $products[] = [];
        }

        for($i = 0; $i < $diviser; $i++){
            $offset = $i*$entries;
            $limit = $offset+$entries;
            //echo($limit);
            for($j = $offset; $j < $limit; $j++){
                array_push($products[$i], $prods[$j]);
            }   
        }
        
        $modulo = $length - ($entries*$diviser);

        if($modulo > 0){
            $products[] = [];
            for($k = $entries*$diviser; $k < $length; $k++){
                array_push($products[$diviser], $prods[$k]);
                 
            }
        }*/


        //dump($products);
        //die();
        return $this->render('home/index.html.twig', [ 'cats' => $cats, 'home_title' => $home_title, 'talk_about_us' => $talk_about_us,
                                'talk_about_us_image' => $talk_about_us_image, 'yt' => $yt, 'yt_desc' => $yt_desc, 'yt_desc2' => $yt_desc2,
                                'etape_desc' => $etape_desc, 'testimonials' => $testimonials, 'sliders' => $sliders, 'active' => $active
           /* 'products' => $products, 'offset' => $off+$lim, 'limit' =>$lim, 'off' => $off, 'prod_length' => $length, */
        ]);
    }

    /**
     * @Route("/newsletter", name="newsletter")
     */

    public function newsletter(Request $request){

        $nl = new Newletter();
        $entityManager = $this->getDoctrine()->getManager();
        $referer = $request->headers->get('referer');
        $email = $request->request->get('newsletter_email');
        $name = $request->request->get('newsletter_name');
        $nl->setCreatedAt(new \DateTime())->setEmail($email)->setName($name);


        
        $__nl = $entityManager->getRepository(Newletter::class)->findOneBy(['email' => $email]);


        if (is_string($email) && is_string($name) && !$__nl) {

            
            $entityManager->persist($nl);
            $entityManager->flush();
            $client = new Client();
            $config = $entityManager->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_key']);
            $confNl = $entityManager->getRepository(Config::class)->findOneBy(['confKey' => 'mc_audience']);
            

           $response = $client->request('POST', 'https://'.explode('-', $config->getValue())[1].'.api.mailchimp.com/3.0/lists/'.$confNl->getValue().'/members', [
                'headers' => [
                    'Authorization' => 'yanndev97 '.$config->getValue(),
                ],

                'json' => [
                    "email_address" => $nl->getEmail(), 
                    "status" => "subscribed", 
                    "merge_fields" => array("FNAME"=> $nl->getName())
                ]
            ]);

            $sub = json_decode($response->getBody()->getContents(), true);
            $nl->setMailchimpId($sub['id']);
            $entityManager->flush();
            return $this->redirect($referer);
        }

        

        return $this->redirect($referer);
    }

    /**
     * @Route("/faq", name="faq")
     */

    public function faq(Request $request){

        
        $entityManager = $this->getDoctrine()->getManager();
        $faqs = $entityManager->getRepository(Faq::class)->findAll();

        return $this->render('home/faq.html.twig', ['faqs' => $faqs]);
    }

    /**
     * @Route("/download-guide", name="download-guide")
     */

    public function downloadGuide(Request $request){

        $guide = new Guide();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(GuideType::class, $guide);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($guide);
            $em->flush();
            $link = $guide->getBook()->getFile();
            if(is_string($link) && $link !== '') 
            {
                $link_arr = explode('/public/', $link);
                $link = isset($link_arr[1]) ? $link_arr[1] : $link;
                
                return $this->file($link);
            }
        }

        return $this->render('home/download-guide.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/product/ask/call", name="customer-ask-call")
     */
    public function askCall(Request $request, SendMail $mailer)
    {
        
        $em = $this->getDoctrine()->getManager();
        $submittedToken = $request->request->get('_token');
        if($this->isCsrfTokenValid('ask-call', $submittedToken)){

            $em = $this->getDoctrine()->getManager();
            $name = $request->request->get('name');
            $firstName = $request->request->get('firstName');
            $email = $request->request->get('email');
            $phone = $request->request->get('phone');
            $message = $request->request->get('message');
            
            $configFromEmail = $em->getrepository(Config::class)->findOneBy(['confKey' => 'from_email_contact']);
            $mailNewDevis = $em->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'demande_rappel']);
            $content = str_replace([ '::name::', '::firstName::', '::email::', '::phone::', '::message::'], 
            [
                $name, $firstName, $email, $phone, $message
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

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/reset/password", name="reset-password")
     */
    public function resetPassword(Request $request, SendMail $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        
        $em = $this->getDoctrine()->getManager();
        $submittedToken = $request->request->get('_token');
        if($this->isCsrfTokenValid('frfu-reset-password', $submittedToken)){

            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get('email');
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            
            if($user){
                $mailNewDevis = $em->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'reset_pw']);
                // Initialisation des caractères utilisables
                $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
                $password = '';
                for($i=0; $i < 10; $i++)
                {
                    $password .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
                }
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $password
                ));
                $em->flush($user);

                $content = str_replace([ '::name::', '::pw::'], 
                [
                    $user->getFirstname(), $password
                ], $mailNewDevis->getContent());

                $data = [
                    'to' => $email,
                    'body' => $content,
                    'subject' => $mailNewDevis->getName()
                ];

                if($mailer->send($data)){
                    $this->addFlash(
                        'success',
                        'Un nouveau mot de passe a été envoyé à votre boite e-mail.'
                    );
                    //

                    return $this->redirectToRoute('app_login');
                }else{
                    $this->addFlash(
                        'error',
                        'Votre nouveau mot de passe n\'a pas pu être envoyé à votre boite e-mail.'
                    );
                }
            }

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('home/pw.html.twig');
    }

}
