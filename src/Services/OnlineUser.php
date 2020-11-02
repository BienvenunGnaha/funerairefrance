<?php


namespace App\Services;

use App\Repository\OnlineUserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;

class OnlineUser
{
    public function addOnlineUser(\App\Entity\OnlineUser $onlineUser, EntityManager $entityManager)
    {
          $entityManager->persist($onlineUser);
          $entityManager->flush();
    }

    public function updateOnlineUser(\App\Entity\OnlineUser $onlineUser, OnlineUserRepository $onlineUserRepository, EntityManager $entityManager)
    {
        //$onlineUserRepository->updateOnlineUser($onlineUser);
        $entityManager->flush($onlineUser);
    }

    public function getOnlineUser(\App\Entity\OnlineUser $onlineUser,OnlineUserRepository $onlineUserRepository)
    {
        return $onlineUserRepository->findByIdUser($onlineUser);
    }

    public function markAsOnline(UserInterface $user, \App\Entity\OnlineUser $onlineUser, OnlineUserRepository $onlineUserRepository, EntityManager $entityManager)
    {
        $ou = $onlineUser;
        $ou->setUtilisateur($user);
        $isAlreadyAdd = $this->getOnlineUser($onlineUser, $onlineUserRepository);

        if ($isAlreadyAdd !== null){
            $isAlreadyAdd->setLastActivityDate(new \DateTime())
                         ->setOnline(true)
                         ->setBusy(false)
            ;

            $this->updateOnlineUser($isAlreadyAdd, $onlineUserRepository, $entityManager);
        }
        else{
            $ou->setLastActivityDate(new \DateTime())
                ->setOnline(true)
                ->setBusy(false);
            ;
            $this->addOnlineUser($ou, $entityManager);
        }
    }

    public function markAsOffline(OnlineUserRepository $onlineUserRepository)
    {
        $onlineUserRepository->updateOfflineUser();
    }
}