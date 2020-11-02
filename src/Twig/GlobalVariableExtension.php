<?php

namespace App\Twig;

use App\Entity\Category;
use App\Entity\Slider;
use App\Entity\Product;
use App\Entity\Config;
use App\Entity\Page;
use App\Entity\User;
use App\Entity\LastMessage;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Services\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class GlobalVariableExtension extends AbstractExtension
{
    /**
     * @var EntityManager
     */
    private $em;

    private $formFactory;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $formFactory){
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomethin']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('global', [$this, 'variableGlobal']),
            new TwigFunction('getConfig', [$this, 'getConfig']), 
            new TwigFunction('getPage', [$this, 'getPage']),
            new TwigFunction('formType', [$this, 'formType']),
            new TwigFunction('floatVal', [$this, 'floatVal']),
            new TwigFunction('unread', [$this, 'unread'])
        ];
    }

    public function floatVal($value){
        return floatval($value);
    }

    public function variableGlobal():array
    {
        
        $cat_menu = $this->em->getRepository(Category::class)->findBy(['isDisplayedInMenu' => true]);
        $slider = $this->em->getRepository(Slider::class)->findBy(['status' => true]);
        //$last_prods = $this->em->getRepository(Product::class)->findBy(['status' => true], ['id' => 'DESC'], 10, 0);
        $config = $this->em->getRepository(Config::class)->findAll();
        $all_pages = $this->em->getRepository(Page::class)->findAll();
        $serializerService = new SerializerService();
        /*$serializer = $serializerService->getSerializer();

        $cat_menu_json = json_decode($serializer->serialize($cat_menu, 'json'), true);
        $cat_menu = $cat_menu_json;*/
        //dump($cat_menu);
        ////die();
        return ['cat_menu' => $cat_menu, 'slider' => $slider, /*'lastProds' => $last_prods,*/ 'all_pages' => $all_pages];
    }

    public function getConfig($key): ?Config
    {

        $config = $this->em->getRepository(Config::class)->findOneBy(['confKey' => $key]);

        return $config;
    }

    public function getPage(array $pages, string $slug): ?Page
    {
        
        $len = count($pages);

        for($i = 0; $i < $len; $i++){
            if($pages[$i] instanceof Page){
                $page = $pages[$i];
                if($page->getSlug() === $slug){
                    return $page;
                }
            }
        }

        return null;
    }

    public function formType($formType)
    {
        return $this->formFactory->create($formType)->createView();
    }

    public function unread(User $user){
        $em = $this->em;
        $lm = $em->getRepository(LastMessage::class)->findOneBy(['user' => $user->getId()]);
        if($lm){
            $count = $lm->getCount();
            if(is_int($count) && $count > 0)
            {
                return $count;
            }
            
        }

        return null;
    }

}
