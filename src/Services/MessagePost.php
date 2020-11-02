<?php
namespace App\Services;


use App\Entity\LastMessages;
use App\Entity\Message;
use App\Events\MessagePostEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Tests\Compiler\D;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagePost
{
    public function addLastMessage(Message $message, LastMessages $lastMessages, UserInterface $receiver, EntityManager $em)
    {
        $lastMessages->setIdMessage($message->getId())
            ->setIdSender($message->getIdSender())
            ->setIdReceiver($message->getIdReceiver())
            ->setUtilisateur($receiver)
            ->setSeen($message->getSeen())
            ->setContent($message->getContent())
            ->setSeenDate(new \DateTime($message->getSeenDate()))
            ->setDeleteState($message->getDeleteState())
            ->setPictures($message->getPictures())
            ->setVideos($message->getVideos())
            ->setSendDate(new \DateTime($message->getSendDate()))
            ;
            $em->persist($lastMessages);
            $em->flush();
    }

    public function updateLastMessage($lastMessages, Message $message, EntityManager $em)
    {

        $lastMessages->setIdMessage($message->getId())
            ->setIdSender($message->getIdSender())
            ->setIdReceiver($message->getIdReceiver())
            ->setSeen($message->getSeen())
            ->setContent($message->getContent())
            ->setSeenDate(new \DateTime($message->getSeenDate()))
            ->setDeleteState($message->getDeleteState())
            ->setPictures($message->getPictures())
            ->setVideos($message->getVideos())
            ->setSendDate(new \DateTime($message->getSendDate()))
        ;

        $em->flush();
    }

    public function process(MessagePostEvent $event)
    {
        $isUserMessageAdded = $event->getEntityManager()->getRepository(LastMessages::class)->findOneBy(array('IdUser' => $event->getReceiver()->getId()));

        if ($isUserMessageAdded === null){
            $lastMessages = new LastMessages();
            $this->addLastMessage($event->getMessage(), $lastMessages, $event->getReceiver(), $event->getEntityManager());
        }
        else{
            $this->updateLastMessage($isUserMessageAdded, $event->getMessage(), $event->getEntityManager());
        }
      $event->initEm();
    }

    public function em(EntityManager $entityManager, UserInterface $user): EntityManager{
        $em = $entityManager;
        $tableName = 'last_messages_'.$user->getId();
        $em->getClassMetadata(LastMessages::class)->setPrimaryTable(array('name' => $tableName));

        return $em;
    }
}