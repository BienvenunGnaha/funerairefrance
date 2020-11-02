<?php


namespace App\Services;

use App\Events\NotificatorEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;


class Notificator
{
   public function processNotificator(NotificatorEvent $notificatorEvent)
   {
       $em = $notificatorEvent->getEm();
       $user = $notificatorEvent->getUser();
       $userTarget = $notificatorEvent->getUserTarget();
       $like = $notificatorEvent->getLike();
       $kiss = $notificatorEvent->getKiss();
       $message = $notificatorEvent->getMessage();
       $type = $notificatorEvent->getType();
       $link = $notificatorEvent->getLink();
       $post = $notificatorEvent->getPost();

       $notificator = new \App\Entity\Notificator();
       $notificator->setUtilisateur($user)
       ->setMessage($message)
       ->setType($type)
       ->setLink($link)
       ->setSeen(false)
       ->setILike($like)
       ->setKiss($kiss)
       ->setPost($post)
       ->setPostDate(new \DateTime())
       ;
       $em->getClassMetadata(\App\Entity\Notificator::class)->setTableName('notificator_'.$userTarget->getId());
       dump($em->getClassMetadata(\App\Entity\Notificator::class)->getTableName());
       $em->persist($notificator);
       $em->flush();
   }
}