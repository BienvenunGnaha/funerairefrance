<?php

namespace App\Controller;

use App\Services\UploadFileManager;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUploadController extends AbstractController
{
    /**
     * @Route("/admin/upload", name="admin_upload")
     */
    public function index()
    {
        return $this->render('admin_upload/index.html.twig', [
            'controller_name' => 'AdminUploadController',
        ]);
    }

    /**
     * @Route("/textarea/upload", name="admin_upload_textarea")
     */
    public function textarea_upload(Request $request){
        $data = $request->files->get('file');
        $module = 'test';
        $folder = 'uploads/'. $module .'/new/original';
        $extension_allowed = array('png', 'jpeg', 'jpg', 'gif');
        $uploader = new UploadFileManager($module);
        $uploader->createDirectory();
        $uploaded = $uploader->uploadFile($data, $folder, 8000000, $extension_allowed);

        return new JsonResponse(array('location' => $uploaded[0]->getPath().'/'.$uploaded[0]->getFileName()), 200);
    }
}
