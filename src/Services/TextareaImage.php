<?php





namespace App\Services;





class TextareaImage

{

    public function searchReplaceImage64(?string $texte, ?string $module){
       if($texte || $module){
        $txt = preg_match_all('#src="(data:image/.+)"#i', $texte, $text);

        $targets = [];

        $content_to_replace = [];

        //dump($text);

        //die();

        if(isset($text[1]) && is_array($text[1])){

            $lenght = count($text[1]);

            if($lenght > 0){

                for($i = 0; $i < $lenght; $i++){

                    $src = explode(',', $text[1][$i]);

                    if(count($src) > 1){



                        $target = '../public/uploads/'.$module.'/images_'.(new \DateTime)->getTimestamp().'_'.uniqid().'.'.explode(';', explode('/', $src[0])[1])[0];

                        if (!file_exists('../public/uploads/'.$module)) mkdir ( '../public/uploads/'.$module , 0777);



                        if(!file_exists($target)){           // Some simple example content.

                            file_put_contents($target, '');     // Save our content to the file.

                        }

                        try {

                            $ifp = fopen($target, 'wb');

                            if($ifp){

                                if (fwrite( $ifp, base64_decode( $src[1] ) )){

                                    $replace = explode('public', $target)[1];

                                    $content_replace = 'src="'.$replace.'"';

                                    $targets[] = $content_replace;

                                    $content_to_replace[] = $text[0][$i];

                                }

                            }



                            // clean up the file resource

                            fclose( $ifp );



                        }catch (Exception $exception){



                        }

                    }



                }

                $t_len = count($targets);

                $c_len = count($content_to_replace);

                if ($t_len !== 0 && $c_len !== 0 && $t_len === $c_len){

                    //dump($content_to_replace);

                    //dump($targets);

                    //die();

                    $content = str_replace($content_to_replace, $targets, $texte);

                    return $content;

                }





            }





        }
       }
        

        return null;

    }

    public function searchReplaceImage64Json(?string $texte, ?string $module){
        if($texte || $module){
         $txt = preg_match_all('#(data:image.+)#i', $texte, $text);
 
         $targets = [];
 
         $content_to_replace = [];
 
         //dump($text);
 
         //die();
 
         if(isset($text[1]) && is_array($text[1])){
 
             $lenght = count($text[1]);
 
             if($lenght > 0){
 
                 for($i = 0; $i < $lenght; $i++){
 
                     $src = explode(',', $text[1][$i]);
 
                     if(count($src) > 1){
 
 
 
                         $target = '../public/uploads/'.$module.'/images_'.(new \DateTime)->getTimestamp().'_'.uniqid().'.'.explode(';', explode('/', $src[0])[1])[0];
 
                         if (!file_exists('../public/uploads/'.$module)) mkdir ( '../public/uploads/'.$module , 0777);
 
 
 
                         if(!file_exists($target)){           // Some simple example content.
 
                             file_put_contents($target, '');     // Save our content to the file.
 
                         }
 
                         try {
 
                             $ifp = fopen($target, 'wb');
 
                             if($ifp){
 
                                 if (fwrite( $ifp, base64_decode( $src[1] ) )){
 
                                     $replace = explode('public', $target)[1];
 
                                     $content_replace = $replace;
 
                                     $targets[] = $content_replace;
 
                                     $content_to_replace[] = $text[0][$i];
 
                                 }
 
                             }
 
 
 
                             // clean up the file resource
 
                             fclose( $ifp );
 
 
 
                         }catch (Exception $exception){
 
 
 
                         }
 
                     }
 
 
 
                 }
 
                 $t_len = count($targets);
 
                 $c_len = count($content_to_replace);
 
                 if ($t_len !== 0 && $c_len !== 0 && $t_len === $c_len){
 
                     //dump($content_to_replace);
 
                     //dump($targets);
 
                     //die();
 
                     $content = str_replace($content_to_replace, $targets, $texte);
 
                     return $content;
 
                 }
 
 
 
 
 
             }
 
 
 
 
 
         }
        }
         
 
         return null;
 
     }

}