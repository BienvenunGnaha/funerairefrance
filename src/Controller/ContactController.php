<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Services\SendMail;
use App\Services\Config;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, SendMail $mailer, Config $config)
    {

        $contact = new Contact();
        $contact->setCreatedAt(new \DateTime());
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            $cnf = $config->getConfig('from_email_contact');
            $data = [
                'to' => $cnf ? $cnf->getValue() : 'contact@funerairefrance.com',
                'body' => '<!DOCTYPE html>
                           <html>
                           <head>
                           <meta charset="utf-8">
                           </head>
                           <body>
                           <p>
                           <span>Nom et Prénoms : </span><span>'.$contact->getName().' '.$contact->getFirstName().'</span>
                           </p>
                           <p>
                           <span>Adresse email : </span><span>'.$contact->getEmail().'</span>
                           </p>
                           <div>
                           <div>Message : </div><div>'.$contact->getMessage().'</div>
                           </div>
                           </body></html>
                ',
                'subject' => $contact->getSubject()
            ];
            $mailer->send($data);
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
