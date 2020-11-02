<?php
namespace App\Services;

use Symfony\Component\Security\Core\Security;
use App\Services\FileManager;

class PostFileManager extends FileManager {
    
    public function __construct($module, Security $security){
        parent::__construct([array('uploads', $security->getUser()->getId(),$module,'pictures'),
                             array('uploads', $security->getUser()->getId(),$module,'videos')], $module, $security);
    }
    public function moveFile()
    {   
        /*$arrayFolder = $this->sortArrayFolder();
        $idUser = $this->idUser();
        $i = 0;
        foreach ($arrayFolder['new'] as $value){
            
            $nbreFiles = $this->countFiles($value);
            $listFiles = $this->listFiles($value);
            $file = $this->lastFileFolder($listFiles);
            $dest_dossier = $arrayFolder['old'][$i];
            $nbreDestFiles = $this->countFiles($dest_dossier);
            $dest_list = $this->listFiles($dest_dossier);
            $dest_file = $this->lastFileFolder($dest_list);
                     
                    
                    if ($nbreFiles > 1) {
                          $info = $this->fileInfo($value.''.$file);
                          
                            if ($dest_file === null) {
                                   
                                    $nbreDest = 0;
                                    rename($value.''.$listFiles[$nbreFiles-2], $dest_dossier.''.$nbreDest.'.'.$info[1]);
                            }
                    
                            else{
                               if (preg_match("#([0-9]{1,})\.[a-z]{1,6}$#", $dest_file, $matches)) {
                                    # code...
                                   $nbreDest = $matches[1] + 1;   
                                   rename($value.''.$listFiles[$nbreFiles-2], $dest_dossier.''.$nbreDest.'.'.$info[1]);
                                }
                            }       
                            
                    }       
                    $i++;
            }  */
        }
    }