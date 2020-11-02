<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Blog::class)->findBy([], ['id' => 'DESC']);
        $blogs = $paginator->paginate(
            $articles, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            8 // Nombre de résultats par page 
        );
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("/blog/show/{title}", name="blog-show")
     */
    public function show($title)
    {

        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Blog::class)->findOneBy(['title' => $title]);
        $blogs = $em->getRepository(Blog::class)->findBy([], ['id' => 'DESC'], 9, 0);
        return $this->render('blog/show.html.twig', [
            'blog' => $blog, 'blogs' => $blogs
        ]);
    }
}
