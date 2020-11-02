<?php


namespace App\Services;


use App\Entity\UserToken;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManager;
use Namshi\JOSE\JWS;
use Namshi\JOSE\SimpleJWS;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Token
{
    private $request;
    private $tokenInterface;
    private $em;
    private $jws;
    public static $fl_r = null;
    public static $fl_0 = null;


   public function __construct(Request $request, UserInterface $token=null, EntityManager $em = null)
   {
       $this->request = $request;
       $this->tokenInterface = $token;
       $this->em = $em;
   }

    public function createToken(){
        $refresh = new RefreshToken();
        $userToken = new UserToken();
        $paylod = $this->getPayload();
        $uic = '';

        $idUser = 0;

        if ($this->getUIC() !== null && $paylod !== null){
            $idUser = $paylod['sub']['id'];
        }else{
            try{
                $idUser = $this->tokenInterface->getId();
            }catch (\Exception $e){
                return new JsonResponse(array('error' => 'User could not be found', 'code' => 4), 404);
            }
        }

        $sub = $this->buildSub($idUser)['sub'];
        $uicWasNull = $this->buildSub($idUser)['uicWasNull'];

        try{
            $jws = $this->buildToken($sub);
            $refreshToken = $refresh->createRefreshToken($sub['idToken']);

        }catch (\Exception $e){

            return new JsonResponse(array('error' => 'Une erreur inattendue s\'est produit',
                                          'details' => $e->getMessage()), 500);
        }


        $date = new \DateTime();
        $expRefreshTimeStamp = $date->getTimestamp() + 2592000;
        $expRefreshDate = new \DateTime("@$expRefreshTimeStamp");

        if ($uicWasNull === true){
            $userToken->setIdSessionConnect($sub['u.ic'])
                    ->setAuthRequired(false)
                    ->setToken($jws->getTokenString())
                    ->setRefreshToken($refreshToken)
                    ->setIatRefreshToken($date)
                    ->setExpRefreshToken($expRefreshDate)
                    ->setIdUser($this->tokenInterface->getId())
                    ->setSessionDate($date);
            $this->em->persist($userToken);
            $this->em->flush();

            $uic = $userToken->getIdSessionConnect();
        }else{
            $u_Token = $this->em->getRepository(UserToken::class)->findOneBy(array('idSessionConnect' => $sub['u.ic']));
            if ($u_Token instanceof UserToken){
                $u_Token->setAuthRequired(false)
                    ->setToken($jws->getTokenString())
                    ->setRefreshToken($refreshToken)
                    ->setIatRefreshToken($date)
                    ->setExpRefreshToken($expRefreshDate);
                $this->em->flush($u_Token);
                $uic = $u_Token->getIdSessionConnect();
            }
            else{
                return new JsonResponse(array('error' => 'User could not be found', 'code' => 4),  401);
            }

        }


        return array('jws' => $jws, 'refreshToken' => $refreshToken, 'u_ic' => $uic);
    }

    public function getToken(){
       $tokenValue = $this->request->headers->get('Authorization');
       $token = explode(' ', $tokenValue);

       return array('token' => JWS::load($token[1]), 'refreshToken' => $token[2]);
    }

    public function verifyToken(){

    }


    public function updateToken(string $uic){

    }

    private function buildToken(array $sub){
        $jws = new SimpleJWS(array('alg' => 'RS256'));
        $jws->setPayload(array('sub' => $sub, 'exp' => time() + 10000));
        $privateKey = openssl_pkey_get_private(file_get_contents(__DIR__.'/private.pem'), PlatFormConstant::SSL_PKEY_PASSPHRASSE);
        $jws->sign($privateKey);

        return $jws;
    }

    private function buildSub(int $id){
       if ($id === 0){
           return new JsonResponse(array('error' => 'User could not be found', 'code' => 4), 401);
       }

        $random = new RandomIdToken();
        $uic = $this->getUIC();
        $this->em->getClassMetadata(UserToken::class)
            ->setTableName('user_token_'.$id);
        $uicWasNull = false;
        if ($this->getUIC() === null){
            $uicWasNull = true;
            $uicAlreadyExists = true;

            while ($uicAlreadyExists === true)
            {
                $uic = $random->createIdUniqConnect($id);
                $u_Token = $this->em->getRepository(UserToken::class)->findOneBy(array('idSessionConnect' => $uic));

                if ($u_Token === null){
                    $uicAlreadyExists = false;
                }
            }
        }

        $sub =[
            'id' => $id,
            'u.ic' => $uic,
            'idToken' => $random->createIdToken($id)
        ];

        return array('sub' => $sub, 'uicWasNull' => $uicWasNull);
    }

    public function getUIC(){
       return $this->request->query->get('u_ic');
    }

    public function getPayload(){
       $authToken = $this->getAuthorizationToken();
       if ($authToken === null || !is_array($authToken)){
           return null;
       }

        $credentials = SimpleJWS::load($authToken[1]);
        $payload = $credentials->getPayload();
        return $payload;
    }

    public function getAuthorizationToken(){

        if (!$tokenValue = $this->request->headers->get('Authorization')){
            return null;
        }

        return explode(' ', $tokenValue);
    }

    public function getUser(){
       $request = new Request();
       $payload = $this->getPayload();
       if ($payload === null){
           return new JsonResponse(array('error' => 'payload can\'t be null' , 'code' => 5));
       }
       $user = $this->em->getRepository(Utilisateur::class)->findOneBy(['id' => $payload['sub']['id']]);
        if (!$user){
            return new JsonResponse(array('error' => 'User could not be found', 'code' => 4), 401);
        }
        return $user;
    }

    public function renderUserAsArray(UserInterface $user){

       return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail() ,
            'birthDay' => $user->getBirthDay() ,
            'roles' => $user->getRoles() ,
            'ageMin' => $user->getAgeMin() ,
            'ageMax' => $user->getAgeMax() ,
            'preference'=> $user->getPreference() ,
            'about' => $user->getAbout() ,
            'sex' => $user->getSex() ,
            'sexuality' => $user->getSexuality() ,
            'situation' => $user->getSituation() ,
            'country' => $user->getCountry() ,
            'town' => $user->getTown() ,
            'profilePictureLink' => $user->getProfilePictureLink() ,
            'idFb' => $user->getIdFb() ,
            'fbFirstName' => $user->getFbFirstName() ,
            'fbLastName' => $user->getFbLastName() ,
            'cougar' => $user->getCougar()
       ];
    }

    /*private function entityManager(int $id){
       return
    } */




}
