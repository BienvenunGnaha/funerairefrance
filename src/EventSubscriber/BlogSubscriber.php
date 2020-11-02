<?php

namespace App\EventSubscriber;

use App\Entity\Blog;
use App\Entity\Product;
use App\Services\TextareaImage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class BlogSubscriber implements EventSubscriberInterface
{
    public function onPrePersist(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Blog)) {
            return;
        }


        $entity = $this->updateDesc($entity);
        $event['entity'] = $entity;
    }

    public function onPreUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Blog)) {
            return;
        }


        $entity = $this->updateDesc($entity);
        $event['entity'] = $entity;
    }

    private function updateDesc(Blog $entity): Blog
    {
        $content  = $entity->getContent();
        $t_image = new TextareaImage();
        $content = $t_image->searchReplaceImage64($content, 'blog');
        if($content) $entity->setContent($content);
        return $entity;
    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_update' => 'onPreUpdate',
            'easy_admin.pre_persist' => 'onPrePersist',
        ];
    }
}
