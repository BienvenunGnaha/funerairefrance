<?php


namespace App\Services;
use App\Entity\Utilisateur;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{


    public function getSerializer(){
        $encoders = new JsonEncoder();
        /*try{

        }catch(EntityNotFoundException $e){
            
        }*/
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                if ($object instanceof Utilisateur){
                    return [
                        'id' => $object->getId(),
                        'username' => $object->getUsername(),
                        'FullName' => $object->getFullName(),
                        'email' => $object->getEmail(),
                        'birthday' => $object->getBirthday(),
                        'ageMin' => $object->getAgeMin(),
                        'ageMax' => $object->getAgeMax(),
                    ];
                }else{
                    return $object;
                }

            },
        ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizers], [$encoders]);
        return $serializer;
    }

    public function getBasicSerializer(){
        $encoders = new JsonEncoder();
        /*try{

        }catch(EntityNotFoundException $e){
            
        }*/
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                /*if ($object instanceof Utilisateur){
                    return [
                        'id' => $object->getId(),
                        'username' => $object->getUsername(),
                        'FullName' => $object->getFullName(),
                        'email' => $object->getEmail(),
                        'birthday' => $object->getBirthday(),
                        'ageMin' => $object->getAgeMin(),
                        'ageMax' => $object->getAgeMax(),
                    ];
                }else{
                    return $object;
                }*/
                return $object;
            },
        ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizers], [$encoders]);
        return $serializer;
    }


}