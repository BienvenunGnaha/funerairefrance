<?php


namespace App\Services;


class RandomIdToken
{
    private $em;
    private $request;
    public function createIdUniqConnect(int $id){
        $uniqIdConnect = hash('sha256', uniqid($id, true));
        return $uniqIdConnect;
    }

    public function createIdToken(int $id){
       return base64_encode($this->createIdUniqConnect($id));
    }
}
