<?php

namespace App\Services;

use App\Entity\Devis;
use Twig\Environment;
use App\Services\CartService;


class DevisService
{
    
    private $cartService;

    private $twig;

    private $projectDir;

    
    public function __construct(Environment $twig, string $projectDir, CartService $cartService)
    {
        $this->twig = $twig;
        $this->projectDir = $projectDir;
        $this->cartService = $cartService;
    }

    public function generateHtml(Devis $devis,array $devis_details, $em)
    {
        $meta = $devis->getMetaData();
        $images = [$meta['backgroundImage']['src']];
        $countObject = count($meta['objects']);
        for($k = 0; $k < $countObject; $k++){
            $mt = $meta['objects'][$k];
            if($mt['type'] == 'image'){
                array_merge($mt, [$mt['src']]);
            }
        }

        // Retrieve the HTML generated in our twig file
        $html = $this->twig->render('product/pdf.html.twig', [
            'devis' => $devis_details, 'images' => $images, 'svg' => $devis->getSvg(), 'deviss' => $devis
        ]);

        return $html;
    }
    
}