<?php

namespace App\Controller;

use App\Entity\Testimonial;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestimonialController extends AbstractController
{
    /**
     * @Route("/temoignages", name="testimonial")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $testimonials = $em->getRepository(Testimonial::class)->findBy(['status' => true]);
        return $this->render('testimonial/index.html.twig', [
            'testimonials' => $testimonials,
        ]);
    }
}
