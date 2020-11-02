<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PaymentDevisController extends AbstractController
{
    /**
     * @Route("/payment/devis", name="payment_devis")
     */
    public function index()
    {
        return $this->render('payment_devis/index.html.twig', [
            'controller_name' => 'PaymentDevisController',
        ]);
    }
}
