<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Services\EncodePassword;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Request;

class UserSubscriber implements EventSubscriberInterface
{

    /**
     * @var EncodePassword
     */
    private $passwordEncoder;

    private $request;

    public function __construct()
    {
        $this->passwordEncoder = new EncodePassword();
    }

    public function onPrePersist(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof User)) {
            return;
        }


        $pass = $entity->getPassword();
        $entity->setPassword($this->passwordEncoder->encode(
            $entity,
            $pass
        ));
        $date = new \DateTime();
        $entity->setCreatedAt($date); 
        $event['entity'] = $entity;
    }

    public function onPreUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();
        //dump($this->passwordEncoder);
        //die();
        if (!($entity instanceof User)) {
            return;
        }


        $pass = $entity->getPassword();
        $entity->setPassword($this->passwordEncoder->encode(
            $entity,
            $pass
        ));
        $event['entity'] = $entity;
    }
 
    public function onPostList(GenericEvent $event)
    {
        $request = $event->getArgument('request');

        if($request->query->get('entity') === 'User' && $request->query->get('action') === 'list' &&
         $request->query->get('menuIndex') === '0' && $request->query->get('submenuIndex') === '1'){

            $subject = $event->getSubject();
            //$event->getSubject()['list']['filters'] = ['type' => 2];
            //$event->getSubject()['dql_filter'] = 'user.type=2';
            //dump($event->getSubject());
            //die();
            //$event->setSubject($subject);
         }

       /* $pass = $entity->getPassword();
        $entity->setPassword($this->passwordEncoder->encode(
            $entity,
            $pass
        ));
        $event['entity'] = $entity;*/
    }

    /*public function encodePassword($user, $password)
    {
        $passwordEncoderFactory = new UserPasswordEncoderInterface();
        return $passwordEncoder->encodePassword(
            $user,
            $password
        );
    }*/

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_update' => 'onPreUpdate',
            'easy_admin.pre_persist' => 'onPrePersist',
            'easy_admin.post_list' => 'onPostList',
        ];
    }
}
