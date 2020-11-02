<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfileType;
use App\Services\UploadFileManager;
use App\Entity\User;
use App\Entity\Config;
use App\Entity\Address;
use App\Entity\Rating;
use App\Form\AddressType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use GuzzleHttp\Client;


class UserDashboardController extends AbstractController
{
    /**
     * @Route("/user/dashboard", name="user_dashboard")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('user_dashboard/index.html.twig', [
            'user' => $this->getUser(),
            'addresses' => $em->getRepository(Address::class)->findBy(['userId' => $this->getUser()]),
            'default' => $em->getRepository(Address::class)->findOneBy(['userId' => $this->getUser(), 'byDefault' => true])
        ]);
    }

    /**
     * @Route("/user/carnet", name="user_carnet")
     */
    public function carnet()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('user_dashboard/carnet.html.twig', [
            'user' => $this->getUser(),
            'addresses' => $em->getRepository(Address::class)->findBy(['userId' => $this->getUser()]),
            'default' => $em->getRepository(Address::class)->findOneBy(['userId' => $this->getUser(), 'byDefault' => true])
        ]);
    }

    /**
     * @Route("/user/ratings/products", name="user_list_rating")
     */
    public function ratings()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('user_dashboard/ratings.html.twig', [
            'ratings' => $em->getRepository(Rating::class)->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/user/rating/{id}", name="user_view_rating")
     */
    public function rating($id)
    {
        $em = $this->getDoctrine()->getManager();
        $rating = $em->getRepository(Rating::class)->find($id);
        $prodRatingLevel = 0;
        $ratings = $em->getRepository(Rating::class)->findBy(['product' => $rating->getProduct()->getId(), 'status' => true]);
        $count = count($ratings);
        for($i = 0; $i < $count; $i++){
            $rtg = $ratings[$i];
            $prodRatingLevel += $rtg->getLevel();
        }

        $prodRatingLevel = (int)($prodRatingLevel/$count);
        return $this->render('user_dashboard/rating.html.twig', [
            'rating' => $rating, 'prodLevel' => $prodRatingLevel, 'ratingsNumber' => $count
        ]);
    }

    /**
     * @Route("/user/news/subscribe", name="user_news_subscribe")
     */
    public function newsSubscribe(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $submittedToken = $request->request->get('_token');

        if($this->isCsrfTokenValid('frfu-user-news-subscribe', $submittedToken)){
            
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $news = $request->request->get('news') == 'on' ? true : false;
            $user->setNews($news);
            $em->flush($user);
            if($news){
                $this->addFlash(
                    'success',
                    'Vous êtes inscrit aux lettres d\'informations avec succès.'
                );
            }else{
                $this->addFlash(
                    'success',
                    'Vous êtes désaboné aux lettres d\'informations avec succès.'
                );
            }
            
            return $this->redirectToRoute('user_dashboard');
        }
        return $this->render('user_dashboard/news-subscribe.html.twig');
    }

     /**
     * @Route("/user/address/{id}/as/default", name="user_address_default")
     */
    public function addressDefault(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository(Address::class)->find($id);
        if($address){
            $addresses = $em->getRepository(Address::class)->findBy(['userId' => $this->getUser()]);
            $count = count($addresses);
            for ($i=0; $i < $count; $i++) { 
                $ad = $addresses[$i];
                $ad->setByDefault(false);
                $em->flush($ad);
            }  
            $address->setByDefault(true);
            $em->flush($address); 
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/user/address/edit/{id}", name="user_address_edit")
     */
    public function addressEdit(Request $request, $id){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository(Address::class)->find($id);
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            
            $em->flush($address);
            $this->addFlash(
                'success',
                'L\'adresse de livraison a été modifiée avec succès.'
            );
            
           return $this->redirectToRoute('user_dashboard');
        }

        return $this->render('user_dashboard/edit-address.html.twig', ['formAddress' => $form->createView(), 'address' => $address]);
    }

    /**
     * @Route("/user/tracking/{colis_id}", name="user_tracking_colis")
     */
    public function track($colis_id){
        $client = new Client(); 
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository(Config::class)->findOneBy(['confKey' => 'api_key_chronopost']);
        if($config){
            try{
                $response = $client->get('https://api.laposte.fr/suivi/v2/idships/'.$colis_id.'?lang=fr_FR', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'X-Okapi-Key' => $config->getValue()
                    ]
                ]);  
                $data = json_decode($response->getBody()->getContents(), true);
                if($response->getStatusCode() === 200){
                    $html = $this->renderView('includes/track-view.html.twig', ['timelines' => $data['shipment']['timeline'], 'events' => $data['shipment']['event']]);
                    return $this->json(['success' => true, 'html' => $html], 200);  
                }
                
            }catch(\Exception $e){
                return $this->json(['success' => false], 500);  
            }
        }
        
        return $this->json(['success' => false], 500);  
    }

    /**
     * @Route("/user/address/delete/{id}", name="user_address_delete")
     */
    public function addressDelete($id){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository(Address::class)->find($id);

        if($address->getUserId()->getId() === $user->getId()){
            
            
            $em->remove($address);
            $em->flush();
            $this->addFlash(
                'success',
                'L\'adresse de livraison a été supprimé.'
            );
            
           
        }
        return $this->redirectToRoute('user_dashboard');
    }

    /**
     * @Route("/user/address/add", name="user_address_add")
     */
    public function addressAdd(Request $request){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $address = new Address();
        $address->setUserId($user);
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $address->setByDefault(false);
            $em->persist($address);
            $em->flush();
            $this->addFlash(
                'success',
                'L\'adresse de livraison a été ajoutée avec succès.'
            );
           return $this->redirectToRoute('user_dashboard');
        }

        return $this->render('user_dashboard/add-address.html.twig', ['formAddress' => $form->createView(), 'address' => $address]);
    }

    /**
     * @Route("/user/edit/password", name="user-reset-pw")
     */
    public function resetPw(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $em = $this->getDoctrine()->getManager();
        $submittedToken = $request->request->get('_token');

        if($this->isCsrfTokenValid('frfu-user-reset-password', $submittedToken)){
            
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $password = $request->request->get('password');
            $newpw = $request->request->get('new_password');
            $newpw_retype = $request->request->get('new_password_retype');
            
            if($newpw == $newpw_retype && $passwordEncoder->isPasswordValid($user, $password)){
                $user->setPassword($passwordEncoder->encodePassword($user, $newpw));
                $em->flush($user);
                $this->addFlash(
                    'success',
                    'Votre mot de passe a été modifié avec succès.'
                );
            }else{
                $this->addFlash(
                    'danger',
                    'Les informations envoyées pour la modification de mot de passe sont invalides.'
                );
            }
                
            

            return $this->redirectToRoute('user-reset-pw');
        }

        return $this->render('user_dashboard/edit-pw.html.twig'); 
    }

    /**
     * @Route("/user/edit", name="user_edit")
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $photo = $form->get('photo')->getData();
            //dump($photo);
            //die();
            
            if($photo instanceof UploadedFile){
                $module = 'user';
                $folder = 'uploads/'. $module .'/new/original';
                $extension_allowed = array('png', 'jpeg', 'jpg', 'gif');
                $uploader = new UploadFileManager($module);
                $uploader->createDirectory();
                $uploaded = $uploader->uploadFile($photo, $folder, 8000000, $extension_allowed);
                $photo = $uploaded[0]->getPath().'/'.$uploaded[0]->getFileName();
                $user->setPhoto($photo);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->flush($user);
            return $this->redirectToRoute('user_dashboard');
        }

        return $this->render('user_dashboard/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
