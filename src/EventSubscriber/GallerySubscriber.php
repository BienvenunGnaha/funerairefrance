<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\Gallery;
use App\Services\TextareaImage;

class GallerySubscriber implements EventSubscriberInterface
{
    public function onEasyAdminPrePersist(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Gallery)) {
            return;
        }


        $entity = $this->uploadImageBase64($entity);
        $event['entity'] = $entity;
        
    }

    public function onEasyAdminPreUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Gallery)) {
            return;
        }


        $entity = $this->uploadImageBase64($entity);
        $event['entity'] = $entity;
    }

    private function uploadImageBase64(Gallery $entity): Gallery
    {
        $content  = json_decode($entity->getMetaData(), true);
        $secondDecode = false;
        if(!is_array($content)){
            $content = json_decode($content, true);
            $secondDecode = true;
        }

        $len = count($content);
        for($i = 0; $i < $len; $i++){
            $t_image = new TextareaImage();
            $file = $t_image->searchReplaceImage64Json($content[$i]['file'], 'gallery');
            if($file) $content[$i]['file'] = $file;
        }
    
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
