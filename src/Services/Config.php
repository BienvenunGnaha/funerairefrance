<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Config as Conf;

class Config
{
    private $em;

    public function __construct(EntityManagerInterface $em){
            $this->em = $em;
    }

    public function getConfig($key): ?Conf
    {
        $config = $this->em->getRepository(Conf::class)->findOneBy(['confKey' => $key]);

        if($config instanceof Conf){

            return $config;
        }
        
        return null;
    }
}