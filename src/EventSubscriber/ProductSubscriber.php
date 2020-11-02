<?php

namespace App\EventSubscriber;

use App\Entity\Blog;
use App\Entity\Product;
use App\Entity\Granit;
use App\Services\TextareaImage;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class ProductSubscriber implements EventSubscriberInterface
{
    public function onKernelTerminate(TerminateEvent $event)
    {
        // ...
    }

    public function onPrePersist(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Product)) {
            return;
        }


        $content  = $entity->getDescription();
        $color = $entity->getColor();
        $size = $entity->getSize();
        //dump('coucou');
        //die();
        $t_image = new TextareaImage();
        $content = $t_image->searchReplaceImage64($content, 'product');
        if($content) $entity->setDescription($content);
        if(!$entity->getCreatedAt()) $entity->setCreatedAt(new \DateTime());
        //if(is_string($color)) $entity->setColor([$color]);
        //if(is_string($size)) $entity->setSize([$size]);

        $event['entity'] = $entity;
    }

    private function addGranit($event){
        $entity = $event->getSubject();
        $granits = $entity->getGranits();
        $countGranits = count($granits);
        $em = $event->getArgument('em');
        $allG = $em->getRepository(Granit::class)->findAll();
        $countAllG = count($allG);
        for($i = 0; $i < $countAllG; $i++){
            $granit = $allG[$i];
            $granit->removeProduct($entity);
            $em->flush($granit);
        }
        dump($event);
        for($j = 0; $j < $countGranits; $j++){
            $granit = $granits[$j];
            if($granit instanceof Granit){
                $granit->addProduct($entity);
                $em->flush($granit);
            }
            
        }
    }

    public function onPreUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Product)) {
            return;
        }


        $content  = $entity->getDescription();
        $color = $entity->getColor();
        $size = $entity->getSize();
        //dump('coucou');
        //die();
        $t_image = new TextareaImage();
        $content = $t_image->searchReplaceImage64($content, 'product');
        if($content) $entity->setDescription($content);
        $this->addGranit($event);
        //if(is_string($color)) $entity->setColor([$color]);
        //if(is_string($size)) $entity->setSize([$size]);

        $event['entity'] = $entity;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.terminate' => 'onKernelTerminate',
            'easy_admin.pre_update' => 'onPreUpdate',
            'easy_admin.pre_persist' => 'onPrePersist',
        ];
    }


}
