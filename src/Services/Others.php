<?php
namespace App\Services;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

class Others{

   public function domainName(Request $request)
   {
   	return 'https://'.$request->headers->all()['host'][0].'/';
   }

   public function idUser(Security $security)
   {
   	return $security->getUser()->getId();
   }

   public function user(Security $security)
   {
   	return $security->getUser();
   }
}