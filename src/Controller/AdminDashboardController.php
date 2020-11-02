<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Campaign;
use App\Entity\Config;
use App\Entity\Gallery;
use App\Entity\Granit;
use GuzzleHttp\Client;
use MailchimpAPI\Mailchimp;
use App\Entity\Newletter;
use App\Services\SendMail;
use App\Entity\Cart;

class AdminDashboardController extends EasyAdminController
{
    /**
     * @Route("/staff/dashboard", name="admin_dashboard")
     */
    public function index()
    {
        return $this->render('admin_dashboard/index.html.twig', [
            'controller_name' => 'AdminDashboardController',
        ]);
    }

    /**
     * @Route(path = "/admin/user/company", name = "admin_user_company")
     */
    public function company(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findBy(['type' => 2]);
        
        return $this->render('admin_dashboard/index.html.twig', [
            //'users' => $users,
            'entity' => 'User'
        ]);
    }

    /**
     * @Route(path = "/admin/gallery/duplicate", name = "admin_gallery_duplicate")
     */
    public function duplicate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Gallery::class);
        $id = $request->query->get('id');
        $entity = $repository->find($id);

        if($entity instanceof Gallery){
            $gallery = new Gallery();
            $gallery->setName($entity->getName())
            ->setGranit($entity->getGranit())
            ->setPrice($entity->getPrice())
            ->setPricepro($entity->getPricepro())
            ->setMetaData(json_decode($entity->getMetaData(), true));
            $em->persist($gallery);
            $em->flush();
        }

        
        return $this->redirect($request->headers->get('referer'));
    }

    

    // /**
     // * @Route(path = "/admin/granit/duplicate", name = "dupliquer_granit")
     // */
    /*public function duplicateGranit(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Granit::class);
        $id = $request->query->get('id');
        $entity = $repository->find($id);

        if($entity instanceof Granit){
            $granit = new Granit();
            $granit->setName($entity->getName())
            ->setGranit($entity->getGranit())
            ->setPrice($entity->getPrice())
            ->setPricepro($entity->getPricepro())
            ->setMetaData(json_decode($entity->getMetaData(), true));
            $em->persist($granit);
            $em->flush();
        }

        
        return $this->redirect($request->headers->get('referer'));
    }*/

     /**
     * @Route(path = "/admin/campaign/send", name = "campaign_send")
     */
    public function sendCampaignAction(Request $request, SendMail $mailer)
    {
        // change the properties of the given entity and save the changes
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Campaign::class);
        $id = $request->query->get('id');
        $entity = $repository->find($id);

        $nl = $em->getRepository(Newletter::class)->findAll();
        $users = $em->getRepository(User::class)->findBy(['news' => true]);
        $conf = $em->getRepository(Config::class)->findOneBy(['confKey' => 'from_email_contact']);
        $mails = [];
        $len = count($nl);
        $lenUsers = count($users);
        for($i = 0; $i < $len; $i++)
        {
            $mails[] = $nl[$i]->getEmail();
        }

        for($i = 0; $i < $lenUsers; $i++)
        {
            $mails[] = $users[$i]->getEmail();
        }

        //dump($mails);
        //die();
        $mailer->send(['to' => $mails, 'body' => $entity->getTemplate()->getContent(), 'subject' => $entity->getSubject()]);

        

        //$confNl = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_auth']);
        //$client = new Client();
        //$mailchimp = new Mailchimp($config->getValue());
        //$mailchimp->campaigns($entity->getMcId())->content()->post();
        //$mailchimp->campaigns($entity->getMcId())->test(['bitsekai.company@gmail.com', 'erinhumphrey.gm.5585009@blackjunky.life'], 'html');
        //$list = $mailchimp->campaigns($entity->getMcId())->checklist()->get();

       // dump(json_decode($list->getBody(), true));
       // dump(json_decode($mailchimp->campaigns($entity->getMcId())->content()->get()->getBody(), true));
       // die();

        //dump('https://'.explode('-', $config->getValue())[1].'.api.mailchimp.com/3.0/campaigns/'.$entity->getMcId().'/actions/send');
        //die();

       /* $response = $client->request('POST', 'https://'.explode('-', $config->getValue())[1].'.api.mailchimp.com/3.0/campaigns/'.$entity->getMcId().'/actions/send', [
            'headers' => [
                'Authorization' => $confNl->getValue().' '.$config->getValue(),
            ],

            'json' => [
                'campaign_id' => $entity->getMcId()
            ]
        ]);*/

        // redirect to the 'list' view of the given entity ...
        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ]);
    }
}
