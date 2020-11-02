<?php
namespace App\Services;

use App\Services\ProfileFileManager;
use App\Services\ImageGenerator;

class ProfileImageGenerator extends ImageGenerator {
    
    protected $fileManager;
    
    public function __construct(ProfileFileManager $profileFileManager){
        $this->fileManager = $profileFileManager;
    }
    
    /**
     * 
     * @param string $originalFile
     */
    public function resizeImage($originalFile)
    {   
        $fileManager = $this->getFileManager();
        $idUser = $fileManager->idUser();
        $module = $fileManager->getModule();
        $sourceDossier = 'uploads/'.$idUser.'/'.$module.'/new/original/';
        $sourceFile = $fileManager->profilePicture($sourceDossier);
        $dimension = getimagesize($sourceDossier.''.$sourceFile);
        $name_extension = $fileManager->fileInfo($sourceDossier.''.$sourceFile);
        $largeur_original = $dimension[0];
        $hauteur_original = $dimension[1];
        $new_old=$fileManager->sortArrayFolderForResizeImage();
        
        foreach ($new_old as $value){
            $destinationDossier = $value;
            $nbreFile = $fileManager->countFiles($destinationDossier);
           
                $list = $fileManager->listFiles($destinationDossier);
                if (preg_match("#([0-9]{1,})\.[a-z]{1,6}$#", $fileManager->lastFileFolder($list), $matches)) {
                    $nbre = $matches[1]+1;
                    
                    $destinationFile = $destinationDossier.''.$nbre.'.'.$name_extension[1];
                }
                else{
                    $nbre = 0;
                    $destinationFile = $destinationDossier.''.$nbre.'.'.$name_extension[1];
                }
                
           
            if ($value == 'uploads/'.$idUser.'/'.$module.'/new/mini/' || $value == 'uploads/'.$idUser.'/'.$module.'/new/avatar/') {
                
                if ($value == 'uploads/'.$idUser.'/'.$module.'/new/mini/') {
                    $this->resize($originalFile, $destinationFile, NULL, 45, 45);
                }
                if ($value == 'uploads/'.$idUser.'/'.$module.'/new/avatar/') {

                    $this->resize($originalFile, $destinationFile, null, 74, 74);
                }
                
            }
            
            if ($value == 'uploads/'.$idUser.'/'.$module.'/new/medium/' || $value == 'uploads/'.$idUser.'/'.$module.'/new/medium_laptop/') {
                if ($value == 'uploads/'.$idUser.'/'.$module.'/new/medium/') {
                    $hauteur = 200;
                    $pourcentage = $hauteur/$hauteur_original;
                    $this->resize($originalFile, $destinationFile, $pourcentage, NULL, NULL);
                }
                
                if ($value == 'uploads/'.$idUser.'/'.$module.'/new/medium_laptop/') {
                    $hauteur = 250;
                    $pourcentage = $hauteur/$hauteur_original;
                    $this->resize($originalFile, $destinationFile, $pourcentage, NULL, NULL);
                }
            }
            
            if ($value == 'uploads/'.$idUser.'/'.$module.'/new/big/' || $value == 'uploads/'.$idUser.'/'.$module.'/new/big_laptop/'){
                
                if ($value == 'uploads/'.$idUser.'/'.$module.'/new/big/') {
                    
                    $this->resize($originalFile, $destinationFile, 1/4, NULL, NULL);
                }
                
                if ($value == 'uploads/'.$idUser.'/'.$module.'/new/big_laptop/') {
                  
                    $this->resize($originalFile, $destinationFile, 1/3, NULL, NULL);
                }
            }
            $nbreFile = NULL;
            $nbre = NULL;
        }
       
    }
    
    public function getFileManager():ProfileFileManager 
    {
        return $this->fileManager;
    }
}