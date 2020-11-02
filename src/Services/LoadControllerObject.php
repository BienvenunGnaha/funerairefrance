<?php


namespace App\Services;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class LoadControllerObject extends AbstractController
{
  public function user(){
      return $this->getUser();
  }
}