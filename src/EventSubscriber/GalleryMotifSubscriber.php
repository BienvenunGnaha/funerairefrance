<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\MotifGallery;
use App\Services\TextareaImage;

class GalleryMotifSubscriber implements EventSubscriberInterface
{
    public function onEasyAdminPrePersist(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof MotifGallery)) {
            return;
        }


        $entity = $this->uploadImageBase64($entity);
        $event['entity'] = $entity;
        
    }

    public function onEasyAdminPreUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof MotifGallery)) {
            return;
        }


        $entity = $this->uploadImageBase64($entity);
        $event['entity'] = $entity;
    }

    private function uploadImageBase64(MotifGallery $entity): MotifGallery
    {
        $content  = json_decode($entity->getMetaData(), true);
        $secondDecode = false;
        if(!is_array($content)){
            $content = json_decode($content, true);
            if(!is_array($content)){
                $content = json_decode($content, true);
                $secondDecode = true;
            }
            $secondDecode = true;
        }
        //dump($content);
        //die();
        $t_image = new TextareaImage();
        $black = $t_image->searchReplaceImage64Json($content['black'], 'motif_gallery');
        $white = $t_image->searchReplaceImage64Json($content['white'], 'motif_gallery');
        $gold = $t_image->searchReplaceImage64Json($content['gold'], 'motif_gallery');
        if($black) $content['black'] = $black;
        if($white) $content['white'] = $white;
        if( $gold) $content['gold'] = $gold;
    
        $entity->setMetaData(json_encode($content));
        return $entity;
    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_persist' => 'onEasyAdminPrePersist',
            'easy_admin.pre_update' => 'onEasyAdminPreUpdate',
        ];
    }
}
