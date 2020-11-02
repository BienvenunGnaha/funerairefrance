<?php
namespace App\Services;

use Symfony\Component\Security\Core\Security;
use App\Services\FileManager;

class UploadFileManager extends FileManager {
    
    public function __construct($module){
        parent::__construct([array('uploads',$module,'new','mini'),
            array('uploads',$module,'new','medium'),
            array('uploads',$module,'new','medium_laptop'),
            array('uploads',$module,'new','big'),
            array('uploads',$module,'new','big_laptop'),
            array('uploads',$module,'new','avatar'),
            array('uploads',$module,'new','original'),
            array('uploads',$module,'old','mini'),
            array('uploads',$module,'old','medium'),
            array('uploads',$module,'old','medium_laptop'),
            array('uploads',$module,'old','big'),
            array('uploads',$module,'old','big_laptop'),
            array('uploads',$module,'old','avatar'),
            array('uploads',$module,'old','original')
        ], $module);
    }
    public function moveFile()
    {   
        $arrayFolder = $this->sortArrayFolder();
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
            }         
        }
    }