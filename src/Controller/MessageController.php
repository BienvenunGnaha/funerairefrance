<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MessageType;
use App\Entity\Message;
use App\Services\UploadFileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\LastMessage;
use App\Entity\User;

class MessageController extends AbstractController
{
    /**
     * @Route("/user/message", name="messages")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $message = new Message();
        $message->setSender($user);
        $form = $this->createForm(MessageType::class, $message);
        $em = $this->getDoctrine()->getManager();
        $msgs = $em->getRepository(Message::class)->listMessages($user);
        $lm = $em->getRepository(LastMessage::class)->findOneBy(['user' => $user->getId()]);
        if($lm){
            $lm->setCount(0);
            $em->flush($lm);
        }
        
        $len = count($msgs);
        $messages = [];
        for($i = $len-1; $i >= 0; $i--){
           array_push($messages, $msgs[$i]);
        }

        return $this->render('message/index.html.twig', [
            'form' => $form->createView(), 'messages' => $messages, 'lm' => $lm
        ]);
    }

    /**
     * @Route("/user/message/previous/{receiver}", name="message-previous")
     */
    public function userMessage(Request $request, $receiver)
    {
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find((int)$receiver);
        $msgs = $em->getRepository(Message::class)->listMessages($user, $request->query->get('offset'));
        $len = count($msgs);
        $messages = [];
        for($i = $len-1; $i >= 0; $i--){
           array_push($messages, $msgs[$i]);
        }


        return $this->render('message/previous.html.twig', ['messages' => $messages]);
    }

    /**
     *  @Route("/user/message/send", name="message-send")
     */
    public function  send(Request $request){
        $user = $this->getUser();
        $message = new Message();
        $message->setSender($user);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        

        if($form->isSubmitted() && $form->isValid()){
            $photo = $form->get('photo')->getData();
            //dump($photo);
            //die();
            
            if($photo instanceof UploadedFile){
                $module = 'user';
                $folder = 'uploads/'. $module .'/new/original';
                $extension_allowed = array('png', 'jpeg', 'jpg', 'gif');
                $uploader = new UploadFileManager($module);
                $uploader->createDirectory();
                $uploaded = $uploader->uploadFile($photo, $folder, 8000000, $extension_allowed);
                $photo = $uploaded[0]->getPath().'/'.$uploaded[0]->getFileName();
                $message->setPhoto($photo);
            }

            $isCustomer = false;
            //dump($user->getRoles());
            //die();
            if($user->getRoles() === ["roles" => "ROLE_USER"]){
                $isCustomer = true;
            }
            $message->setIsCustomer($isCustomer);
            $message->setCreatedAt(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $lm = $em->getRepository(LastMessage::class)->findOneBy(['user' => $user->getId()]);
            if($lm){
                $lm->setMessage($message);
                $lm->setLastAt(new \DateTime());
                $em->flush($lm);
            }else{
                $lm = new LastMessage();
                $lm->setUser($user)->setMessage($message)->setLastAt(new \DateTime());
                $lm->setCount(0);
                $em->persist($lm);
                $em->flush();
            }
            return $this->render('message/send.html.twig',['message' => $message]);
        }

        return $this->json([], 500);
    }

    /**
     * @Route("/admin/message", name="message-admin")
     */
    public function indexAdmin(Request $request)
    {
        $user = $this->getUser();
        $message = new Message();
        $message->setSender($user);
        
        $em = $this->getDoctrine()->getManager();
        //
        $lms = $em->getRepository(LastMessage::class)->findLast($user);
        $userFirst = null;
        $msgs = null;
        if(isset($lms[0])){
            $userFirst = $lms[0]->getUser();
            if($userFirst instanceof User){
                $msgs = $em->getRepository(Message::class)->listMessages($userFirst);
            }
        }
        $messages = [];
        if($userFirst && $msgs){
            $len = count($msgs);
            for($i = $len-1; $i >= 0; $i--){
                array_push($messages, $msgs[$i]);
            }
            if($len > 0){
                $message->setReceiver($userFirst);
            }
        }
        $form = $this->createForm(MessageType::class, $message);
        return $this->render('message/admin-index.html.twig', [
            'form' => $form->createView(), 'messages' => $messages, 'lms' => $lms
        ]);
    }

    /**
     * @Route("/admin/message/receiver/{receiver}", name="message")
     */
    public function receiverMessage(Request $request, $receiver)
    {
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find((int)$receiver);
        $msgs = $em->getRepository(Message::class)->listMessages($user, $request->query->get('offset'));
        $len = count($msgs);
        $messages = [];
        for($i = $len-1; $i >= 0; $i--){
           array_push($messages, $msgs[$i]);
        }


        return $this->render('message/receiver.html.twig', ['messages' => $messages]);
    }

    /**
     *  @Route("/admin/message/send/", name="message-admin-send")
     */
    public function adminSend(Request $request){
        $user = $this->getUser();
        $message = new Message();
        $message->setSender($user);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        

        if($form->isSubmitted() && $form->isValid()){
            $photo = $form->get('photo')->getData();
            //dump($photo);
            //die();
            
            if($photo instanceof UploadedFile){
                $module = 'user';
                $folder = 'uploads/'. $module .'/new/original';
                $extension_allowed = array('png', 'jpeg', 'jpg', 'gif');
                $uploader = new UploadFileManager($module);
                $uploader->createDirectory();
                $uploaded = $uploader->uploadFile($photo, $folder, 8000000, $extension_allowed);
                $photo = $uploaded[0]->getPath().'/'.$uploaded[0]->getFileName();
                $message->setPhoto($photo);
            }

            $isCustomer = false;
            //dump($user->getRoles());
            //die();
            if($user->getRoles() === ["roles" => "ROLE_USER"]){
                $isCustomer = true;
            }
            $message->setIsCustomer($isCustomer);
            $message->setCreatedAt(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $lm = $em->getRepository(LastMessage::class)->findOneBy(['user' => $user->getId()]);
            $lmRcv = $em->getRepository(LastMessage::class)->findOneBy(['user' => $message->getReceiver()->getId()]);
            if($lm){
                $lm->setMessage($message);
                $lm->setLastAt(new \DateTime());
                $lm->setCount($lm->getCount()+1);
                $em->flush($lm);
            }else{
                $lm = new LastMessage();
                $lm->setUser($user)->setMessage($message)->setLastAt(new \DateTime());
                $lm->setCount(1);
                $em->persist($lm);
                $em->flush();
            }

            if($lmRcv){
                $lmRcv->setMessage($message);
                $lmRcv->setLastAt(new \DateTime());
                $lmRcv->setCount($lmRcv->getCount()+1);
                $em->flush($lmRcv);
            }else{
                $lmRcv = new LastMessage();
                $lmRcv->setUser($message->getReceiver())->setMessage($message)->setLastAt(new \DateTime());
                $lmRcv->setCount(1);
                $em->persist($lmRcv);
                $em->flush();
            }
            return $this->json(['success' => true], 200);
        }

        return $this->json([], 500);
    }


}
