<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\SecurityAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Services\SendMail;
use App\Entity\EmailTemplate;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, SendMail $mailer, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, SecurityAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        //dump($request->request);
        //die();
     
        if ($form->isSubmitted()) {
            //dump($request->request->get('registration_form')['phone']);
            //die();
            $user->setPhone($request->request->get('registration_form')['phone']); 
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(array('roles' => 'ROLE_USER'));
            $user->setIsVerified(false);
            $user->setNews(false);
            $entityManager = $this->getDoctrine()->getManager();
            $exists = $entityManager->getRepository(User::class)->findOneBy(['email' => $request->request->get('registration_form')['email']]);
            if($exists){
                $this->addFlash(
                    'danger',
                    'Cette adresse email est déjà utilisée pour un autre compte.'
                );

                return $this->redirect($request->headers->get('referer'));
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $mailNewDevis = $entityManager->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'activation_link']);
            $user->setResetPwToken(rand(100100, 999999));
            $entityManager->flush($user);
            $content = str_replace([ '::name::', '::link::'], 
            [
                $user->getFirstName(), 'https://funerairefrance.com/activate/account/process/'.$user->getId().'/'.$user->getResetPwToken()
            ], $mailNewDevis->getContent());
            $dataMail = [
                'to' => $user->getEmail(),
                'body' => $content,
                'subject' => $mailNewDevis->getName()
            ];

            if($mailer->send($dataMail)){
                $this->addFlash(
                    'success',
                    'Un mail vous a été envoyé dans votre boite email. Veuillez vérifiez dans les SPAMS si vous ne le trouvez pas.'
                );
            }else{
                $this->addFlash(
                    'danger',
                    'Un mail vous a été envoyé dans votre boite email. Veuillez vérifiez dans les SPAMS si vous ne le trouvez pas.<a href="'.$this->generateUrl('user_activation').'">Activer votre compte</a>'
                );
            }

            
    
    
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activate/account", name="user_activation")
     */
    public function activate(Request $request, SendMail $mailer){
        $em = $this->getDoctrine()->getManager();
        $submittedToken = $request->request->get('_token');
        if($this->isCsrfTokenValid('frfu-activate-account', $submittedToken)){

            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get('email');
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            
            if($user){
            $mailNewDevis = $em->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'activation_link']);
            $user->setResetPwToken(rand(100100, 999999));
            $em->flush($user);
            $content = str_replace([ '::name::', '::link::'], 
            [
                $user->getFirstname(), 'https://funerairefrance.com/activate/account/process/'.$user->getId().'/'.$user->getResetPwToken()
            ], $mailNewDevis->getContent());
            $data = [
                'to' => $email,
                'body' => $content,
                'subject' => $mailNewDevis->getName()
            ];

            if($mailer->send($data)){
                $this->addFlash(
                    'success',
                    'Un mail d\'activation de compte vous a été envoyé.'
                );
            }else{
                $this->addFlash(
                    'danger',
                    'Le mail d\'activation de compte n\'a pu être effectué.'
                );
            }
            

            return $this->redirect($request->headers->get('referer'));
            }
            
        }

        return $this->render('security/activation.html.twig', [
        ]);
    
    }

     /**
     * @Route("/activate/account/process/{id}/{token}", name="user_activation_process")
     */
    public function activateProcess(Request $request, $id, $token, GuardAuthenticatorHandler $guardHandler, SecurityAuthenticator $authenticator){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['id' => (int)$id, 'resetPWToken' => $token]);
        if($user){
            $user->setResetPWToken(null)->setIsVerified(true);
            $em->flush($user);
            $this->addFlash(
                'success',
                'Votre compte a été activé avec succès.'
            );

            if(!$this->getUser()){
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }
            return $this->redirect($this->generateUrl('user_dashboard'));
        }

        $this->addFlash(
            'danger',
            'Les informations d\'activation de compte sont invalides.'
        );


        return $this->redirect($this->generateUrl('user_activation'));
    
    }
}
