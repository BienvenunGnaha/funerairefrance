<?php

namespace App\Services;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Yaml\Yaml;

class EncodePassword
{

    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    /**
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactory $encoderFactory
     */
    public function __construct()
    {
        try{
            $value = Yaml::parseFile('../config/packages/security.yaml');
            $this->encoderFactory = new EncoderFactory($value['security']['encoders']);
        }catch(\Exception $e){

        }
        
    }

    public function getEncoderFactory(){
        return $this->encoderFactory;
    }

    /**
     * @param UserInterface $user
     * @param string $plaintextPassword
     */
    public function encode(UserInterface $user, $plaintextPassword)
    {
        $hash = $this->encoderFactory->getEncoder($user)->encodePassword($plaintextPassword,   $user->getSalt());
        return $hash; 
    }
}