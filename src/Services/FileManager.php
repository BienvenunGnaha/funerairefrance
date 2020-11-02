<?php

namespace App\Services;



use Symfony\Component\Security\Core\Security;

use Symfony\Component\HttpFoundation\File\UploadedFile;



abstract class FileManager{

    /**

     *

     * @var array

     */

    protected $arrayFolder;



    

    /**

     *

     * @var string

     */

    protected $module = '';

    public static $files = [];

    

    /**

     * 

     * @param array $arrayFolder

     * @param Security $security

     */

    

    function __construct($arrayFolder, $module='')

    {

        $this->setArrayFolder($arrayFolder);

        $this->setModule($module);

    }

    

    

    

    abstract function moveFile();

    

    /**

     * 

     * @param UploadedFile $uploadedFile

     * @param string $folder

     * @param mixed $size

     * @param strin $extension_allowed

     * @param string $prefixe

     * @param boolean $isMultiple

     * @return array

     * @throws \RuntimeException

     */

    

    function uploadFile($uploadedFile,$folder,$size,$extension_allowed,$prefixe='',$isMultiple=false) 

    {

        

        if ($isMultiple  === false) {

            if(isset($uploadedFile) && $uploadedFile->getError()==0 && $uploadedFile instanceof uploadedFile){

                

                //if((is_int($size) && $uploadedFile->getSize() <= $size) || $size == '*'){

                    $extension_upload = strtolower($uploadedFile->getClientOriginalExtension());

                    //try{

                        $name = $this->fileName($folder,$extension_upload,$extension_allowed,$prefixe);

                        

                        $target = $uploadedFile->move($folder,$name.'.'.$extension_upload);

                        $fileTarget[] = $target;

                        return $fileTarget;

                    /*} catch (Exception $ex) {



                    }*/

                /*}

                

                else{

                    throw new \RuntimeException('La taille de l\'image dépasse la taille maximaille autorisée');

                }*/

            }

        }

        else {

            $countfiles = count($uploadedFile);

            $fileTarget = array();

            

            

            for ($i = 0; $i < $countfiles; $i++) {

                if(isset($uploadedFile[$i]) && $uploadedFile[$i]->getError()==0 && $uploadedFile[$i] instanceof UploadedFile){

                

                    //if((is_int($size) && $uploadedFile->getSize() <= $size) || $size == '*'){
    
                        $extension_upload = strtolower($uploadedFile[$i]->getClientOriginalExtension());
    
                        //try{
    
                            $name = $this->fileName($folder,$extension_upload,$extension_allowed,$prefixe);
    
                            
    
                            $target = $uploadedFile[$i]->move($folder,$name.'.'.$extension_upload);
    
                            $fileTarget[] = $target->getPath().'/'.$target->getFilename();
                            
    
                        /*} catch (Exception $ex) {
    
    
    
                        }*/
    
                    /*}
    
                    
    
                    else{
    
                        throw new \RuntimeException('La taille de l\'image dépasse la taille maximaille autorisée');
    
                    }*/
    
                }
    

                 

            }
            
            return $fileTarget;

            

        }

               

    }

    

    private function fileName($folder,$extension_upload,$extension_allowed,$prefixe)

    {

        

        if (in_array(strtolower($extension_upload),$extension_allowed))

        {

            

                $i= (int)$this->countFiles($folder);

                $list = $this->listFiles($folder);

                $file = $this->lastFileFolder($list);

                if ($file === null) {

                    $name = 0;

                    

                    return $prefixe.''.$name;

                }

                else{

                    

                    preg_match("#([0-9]{1,})\.[a-z]{1,6}$#", $file, $matches);

                    $name = $matches[1]+1;

                    return $prefixe.''.$name;

                }

        }

        else{

            throw new \RuntimeException('Format non autorisé');

        }

    }

    

    public function listFiles($folder) 

    {

        $listFiles = \scandir($folder);

        $arrayFile = [];

        foreach ($listFiles as $file) {

            if (!preg_match("#[.]{1,}$#", $file)){

                   $arrayFile[]=$file;

            }

        }

        

        usort($arrayFile, "strnatcmp");

        $arrayFileSorted = [];

        

        foreach ($arrayFile as $value) {

            $arrayFileSorted[] = $value;

        }

        

        return $arrayFileSorted;

    }

    

    public function countFiles($folder) 

    {

        

        $length = \count($this->listFiles($folder));

        

        return $length;

    }

    

    /**

     * 

     */

    

    public function createDirectory() 

    {

        foreach ($this->getArrayFolder() as $value) {

             

             foreach ($value as $dossier){

                if ($dossier == 'uploads') {

                    unset($tab);

                    $tab[] = $dossier;

                }else{

                    $tab[] = $dossier;

                }

                $dir = implode('/', $tab);

                $directory = $dir.'/';

                if (!file_exists($directory)) {

                    mkdir($directory);

                }

            }

            

        }

        

    }

    

    public function profilePicture($folder)

    {

        $listFiles = $this->listFiles($folder);

        $file = $this->lastFileFolder($listFiles);

        return $file;

    }

    

    public function lastFileFolder($arrayFileSorted): ?string

    {    

        $nbreFile = \count($arrayFileSorted); 

        return $nbreFile === 0 ? null : $arrayFileSorted[$nbreFile - 1];

    }



        public function deleteFiles($fileNames):void 

    {

        foreach ($fileNames as $value) {

            unlink($value);

        }

    }

    

    /*public function idUser():int

    {

        return $this->getSecurity()->getUser()->getId();

    }*/

    

    function sortArrayFolder() {

        $new_old = array();

        foreach ($this->getArrayFolder() as $value) {

            $dossier = implode('/', $value).'/';

                  

                if (preg_match("#new#i", $dossier)) {

                    $new_old['new'][] = $dossier;

                }

                if (preg_match("#old#i", $dossier)) {

                    $new_old['old'][] = $dossier;              

            }

            

        }

        return $new_old;

    }

    

    public function fileInfo($fileName):array 

    {

        $varExtension = explode('.', $fileName);

        $lengthVarExtension = \count($varExtension);

        $varName = explode('/', $varExtension[$lengthVarExtension-2]);

        $lengthVarName = \count($varName);

        

        return array($varName[$lengthVarName-1], $varExtension[$lengthVarExtension-1]);

    }

    

    public function sortArrayFolderForResizeImage():array

    { 

        $new_old = array();

        foreach ($this->getArrayFolder() as $value) {

            $dossier = implode('/', $value).'/';

            if ($dossier != 'uploads/'.$this->getModule().'/new/original/' && $dossier != 'uploads/'.$this->getModule().'/old/original/') {

                               

                    $new_old[] = $dossier;            

            }

                      

        }

        return $new_old; 

    }



    public function setArrayFolder($arrayFolder)

    {    

            $this->arrayFolder = $arrayFolder;   

    }

    

    public function getArrayFolder():array

    {

        return $this->arrayFolder;

    }

    

    protected function setSecurity(Security $security)

    {    

            $this->security = $security;   

    }

    

    public function getSecurity():Security

    {

        return $this->security;

    }

    

    protected function setModule($module)

    {    

            $this->module = $module;   

    }

    

    public function getModule():string

    {

        return $this->module;

    }

    

}