<?php
namespace App\Services;

abstract class ImageGenerator{
    
    
    abstract function resizeImage($originalFile);
    
    function resize($source_file, $destination_file, $pourcentage, $width, $height) {
        
        
        $details = $this->fileInfo($source_file);
        if (file_exists($source_file)) {
            if ($details[1] == 'jpg' || $details[1] == 'jpeg') {
                
                $source=imagecreatefromjpeg($source_file);
                
                $largeur_source=imagesx($source);
                $hauteur_source=imagesy($source);
                if ($pourcentage != NULL && $width == NULL && $height == NULL) {
                    $destination=imagecreatetruecolor($largeur_source*$pourcentage, $hauteur_source*$pourcentage);
                }
                
                if ($pourcentage == NULL && $width != NULL && $height != NULL) {
                    $destination=imagecreatetruecolor($width, $height);
                }
                
                $largeur_destination=imagesx($destination);
                $hauteur_destination=imagesy($destination);
                
                imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
                imagejpeg($destination, $destination_file);
                
            }
            
            if ($details[1] == 'png') {
                
                $source=imagecreatefrompng($source_file);
                
                $largeur_source=imagesx($source);
                $hauteur_source=imagesy($source);
                if ($pourcentage != NULL && $width == NULL && $height == NULL) {
                    $destination=imagecreatetruecolor($largeur_source*$pourcentage, $hauteur_source*$pourcentage);
                }
                
                if ($pourcentage == NULL && $width != NULL && $height != NULL) {
                    $destination=imagecreatetruecolor($width, $height);
                }
                
                $largeur_destination=imagesx($destination);
                $hauteur_destination=imagesy($destination);
                
                imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
                imagepng($destination, $destination_file);
                
            }
            
            if ($details[1] == 'gif') {
                
                $source=imagecreatefromgif($source_file);
                
                $largeur_source=imagesx($source);
                $hauteur_source=imagesy($source);
                if ($pourcentage != NULL && $width == NULL && $height == NULL) {
                    $destination=imagecreatetruecolor($largeur_source*$pourcentage, $hauteur_source*$pourcentage);
                }
                
                if ($pourcentage == NULL && $width != NULL && $height != NULL) {
                    $destination=imagecreatetruecolor($width, $height);
                }
                
                $largeur_destination=imagesx($destination);
                $hauteur_destination=imagesy($destination);
                
                imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
                imagegif($destination, $destination_file);
                
            }
        }
        
    }
    
    public function fileInfo($fileName):array 
    {
        $varExtension = explode('.', $fileName);
        $lengthVarExtension = \count($varExtension);
        $varName = explode('/', $varExtension[$lengthVarExtension-2]);
        $lengthVarName = \count($varName);
        
        return array($varName[$lengthVarName-1], $varExtension[$lengthVarExtension-1]);
    }
}