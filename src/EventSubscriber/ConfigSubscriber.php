<?php

namespace App\EventSubscriber;

use App\Entity\Config;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ConfigSubscriber implements EventSubscriberInterface
{
    public function onPreDelete(GenericEvent $event)
    {
        $request = $event->getArgument('request');
        $em = $event->getArgument('em');
        $entity = $em->getRepository(Config::class)->find($request->query->get('id'));

        if (!($entity instanceof Config)) {
            return;
        }

        if ($entity->getRequired() === true){
            //dump($request->server->get('HTTP_REFERER'));
            //die();
            //$em =  new EntityManager();
            $event->setArgument('em', null);
            new RedirectResponse($request->server->get('HTTP_REFERER'));
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_delete' => 'onPreDelete',
        ];
    }
}
