<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\EmailTemplate;
use App\Entity\Config;
use GuzzleHttp\Client;

class EmailTemplateSubscriber implements EventSubscriberInterface
{
    public function onEasyAdminPostPersist(GenericEvent $event)
    {
        $et = $event->getSubject();
        $em = $event->getArgument('em');
        if(!$et instanceof EmailTemplate){
            return;
        }

            $config = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_key']);
            $confNl = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_auth']);
            $client = new Client();

           $response = $client->request('POST', 'https://'.explode('-', $config->getValue())[1].'.api.mailchimp.com/3.0/templates', [
                'headers' => [
                    'Authorization' => $confNl->getValue().' '.$config->getValue(),
                ],

                'json' => [
                    "name" => $et->getName(), 
                    "html" => $et->getContent(),
                    
                ]
            ]);

            $sub = json_decode($response->getBody()->getContents(), true);
            $et->setMcTemplateId($sub['id']);
            $em->flush($et);
    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.post_persist' => 'onEasyAdminPostPersist',
        ];
    }
}
