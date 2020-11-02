<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\EmailTemplate;
use App\Entity\Config;
use GuzzleHttp\Client;
use App\Entity\Campaign;

class CampaignSubscriber implements EventSubscriberInterface
{
    public function onEasyAdminPostPersist(GenericEvent $event)
    {
        $et = $event->getSubject();
        $em = $event->getArgument('em');
        
        if(!$et instanceof Campaign){
            return;
        }

            $config = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_key']);
            $confNl = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_auth']);
            $list = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mc_audience']);
            $client = new Client();

           $response = $client->request('POST', 'https://'.explode('-', $config->getValue())[1].'.api.mailchimp.com/3.0/campaigns', [
                'headers' => [
                    'Authorization' => $confNl->getValue().' '.$config->getValue(),
                ],

                'json' => [
                    "recipients" => ['list_id' => $list->getValue()],
                    "type" => $et->getType(), 
                    "setting" => [
                        'subject_line' => $et->getSubject(),
                        'from_name' => $et->getFromName(),
                        "reply_to" => 'bienvenu420@gmail.com',
                        'template_id' => $et->getTemplate()->getMcTemplateId()
                    ]
                ]
            ]);

            $sub = json_decode($response->getBody()->getContents(), true);
            
            $et->setMcId($sub['id']);
            $em->flush($et);
    }

    public function onEasyAdminPostUpdate(GenericEvent $event)
    {
        $et = $event->getSubject();
        $em = $event->getArgument('em');
        
        if(!$et instanceof Campaign){
            return;
        }

            $config = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_key']);
            $confNl = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mailchimp_auth']);
            $list = $em->getRepository(Config::class)->findOneBy(['confKey' => 'mc_audience']);
            $client = new Client();

            $response = $client->request('PATCH', 'https://'.explode('-', $config->getValue())[1].'.api.mailchimp.com/3.0/campaigns/'.$et->getMcId(), [
                'headers' => [
                    'Authorization' => $confNl->getValue().' '.$config->getValue(),
                ],

                'json' => [
                    "recipients" => ['list_id' => $list->getValue()],
                    "type" => $et->getType(), 
                    "setting" => [
                        'subject_line' => $et->getSubject(),
                        'from_name' => $et->getFromName(),
                        "reply_to" => 'bienvenu420@gmail.com',
                        'template_id' => $et->getTemplate()->getMcTemplateId()
                    ]
                ]
            ]);

            $sub = json_decode($response->getBody()->getContents(), true);
            
            $et->setMcId($sub['id']);
            $em->flush($et);
    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.post_persist' => 'onEasyAdminPostPersist', 
            'easy_admin.post_update' => 'onEasyAdminPostUpdate', 
        ];
    }
}
