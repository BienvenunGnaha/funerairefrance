<?php


namespace App\Services;


class MessageTableSuffix
{
   public function suffix(int $idSender, int $idReceiver){
       return ($idSender < $idReceiver) ? $idSender.'_'.$idReceiver : $idReceiver.'_'.$idSender;
   }
}