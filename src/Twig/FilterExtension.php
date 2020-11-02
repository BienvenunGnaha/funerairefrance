<?php

namespace App\Twig;

use App\Entity\Category;
use App\Entity\Slider;
use App\Entity\Product;
use App\Entity\Config;
use App\Entity\Page;
use App\Entity\User;
use App\Entity\Cart;
use App\Entity\Devis;
use App\Entity\LastMessage;
use App\Repository\CategoryRepository;
use App\Services\CartService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Services\SerializerService;

class FilterExtension extends AbstractExtension
{
    /**
     * @var EntityManager
     */
    private $em;

    private $formFactory;

    private $projectDir;

    private $cartService;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $formFactory, string $projectDir, CartService $cartService){
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->projectDir = $projectDir;
        $this->cartService = $cartService;
    } 

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cat_item_number', [$this, 'cat_item_number']),
            new TwigFunction('subcat_item_number', [$this, 'subcat_item_number']),
            new TwigFunction('load_option_by_entity', [$this, 'load_option_by_entity']),
            new TwigFunction('json_object', [$this, 'json_object']),
            new TwigFunction('decode_meta', [$this, 'decode_meta']),
            new TwigFunction('castInteger', [$this, 'castInteger']),
            new TwigFunction('load_option_entity_status', [$this, 'load_option_entity_status']),
            new TwigFunction('load_by_id', [$this, 'load_by_id']),
            new TwigFunction('price_cart', [$this, 'price_cart']),
            new TwigFunction('find_one_by', [$this, 'find_one_by']),
            new TwigFunction('find_by', [$this, 'find_by']),
            new TwigFunction('devis_details', [$this, 'devis_details']),
            new TwigFunction('day_month', [$this, 'day_month'])
        ];
    }

    public function day_month(){
        $timestamp = time() + (24*3600*4);
        $date = date('d/m', $timestamp);
        dump($date);
        return $date;//load_by_id
    }


    public function load_option_by_entity($entity){
        return $this->em->getRepository($entity)->findAll();//load_by_id
    }

    public function find_one_by($entity, $critere){
        return $this->em->getRepository($entity)->findOneBy($critere);
    }

    public function find_by($entity, $critere){
        return $this->em->getRepository($entity)->findBy($critere);
    }

    public function load_option_entity_status($entity){
        return $this->em->getRepository($entity)->findBy(['status' => true]);
    }

    public function load_by_id($entity, $id){
        return $this->em->getRepository($entity)->find($id);
    }

    public function subcat_item_number(int $id, $item, $value)
    {
        $em = $this->em;
        return $em->getRepository(Product::class)->findBy(['subCatId' => $id, 'status' => true, $item => $value]);
    }

    public function cat_item_number(int $id, $item, $value)
    {
        $em = $this->em;
        return $em->getRepository(Product::class)->findBy(['catId' => $id, 'status' => true, $item => $value]);
    }

    public function json_object($object)
    {
        $serializerService = new SerializerService();
        $serializer = $serializerService->getSerializer();
        return json_decode($serializer->serialize($object, 'json'), true);
    }

    public function decode_meta($value)
    {
       
        return json_decode(json_decode($value), true);
    }

    public function castInteger($value)
    {
       
        return (int)$value;
    }

    public function price_cart(Cart $cart, ?User $user){

        return $this->cartService->cartPrice($cart, $user, $this->em);
    }

    public function devis_details(Devis $devis, ?User $user){

        $cartService = new CartService($this->em, $this->projectDir);

        return $cartService->devis($devis, $user, $this->em);
    }


}
