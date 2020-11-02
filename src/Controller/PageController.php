<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Page;

class PageController extends AbstractController
{
    /**
     * @Route("/page-{slug}", name="page")
     */
    public function index($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository(Page::class)->findOneBy(['slug' => $slug]);
        return $this->render('page/index.html.twig', [
            'page' => $page,
        ]);
    }
}
