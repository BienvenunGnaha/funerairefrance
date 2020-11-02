<?php


namespace App\Services;


class RefreshToken
{

      public function createRefreshToken(string $uniqKey){
         return base64_encode($uniqKey);
      }

      public function verifyRefreshToken(){

      }
}
