<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\Devis;
use App\Services\CartService;

class DevisSubscriber implements EventSubscriberInterface
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function onEasyAdminPreUpdate(GenericEvent $event)
    {
        
        $entity = $event->getSubject();

        if (!$entity instanceof Devis) {
            return;
        }
        
        if ($entity->getOtherFees() !== null && $entity->getDiscount() !== null) {
           // die();
            $em = $event->getArgument('em');
            $devis_details = $this->cartService->devis($entity, $entity->getUser(), $em);
            if($entity->getUser()){
                if($entity->getUser()->getType()){
                    if($entity->getUser()->getType()->getId() == 2){
                        $entity->setTotal($devis_details['totalHT']);
                        $entity->setTotalTtc($devis_details['total']);
                    }else{
                        $entity->setTotal($devis_details['total']);
                        $entity->setTotalTtc($devis_details['total']);
                    }
                }else{
                    $entity->setTotal($devis_details['total']);
                    $entity->setTotalTtc($devis_details['total']);
                }
            }
            else{
                $entity->setTotal($devis_details['total']);
                $entity->setTotalTtc($devis_details['total']);
            }
        }

        $event['entity'] = $entity;
    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_update' => 'onEasyAdminPreUpdate',
        ];
    }
}
