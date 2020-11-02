<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Testimonial;
use App\Entity\Page;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends AbstractController
{
    /**
     * @Route("/articles/{id}", name="category_product")
     */
    public function index(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $offset = (int)$request->query->get('offset') != 0 ? (int)$request->query->get('offset') : 0;
        $cat_product = $em->getRepository(Category::class)->findOneBy(['id' => $id]);
        
        $cat_products = $em->getRepository(Product::class)->findBy(['catId' => $id], ['createdAt' => 'DESC'], 12, $offset);
        //$steles = $em->getRepository(Stele::class)->find(['id' => $id]);
        $queries = $request->query->all();
        $params = ['catId' => $id];
        $sortBy = [];
        if($queries !== []){
            $needDql = false;
            $needAddOrder = false;
            $comp = '';
            foreach($queries as $key => $value){
                $val = $request->query->get($key);
                if($val && $val !== ''){
                    if($key === 'sortBy' || $key === 'order'){ 
                        $sortBy[$key] = $val;
                    }
                    else{

                        if($key != 'offset'){
                            if($key == 'granits'){
                                $needDql = true;
                                $val = [(int)$val];
                            }
                            if($key == 'budget'){
                                $needDql = true;
                                $val = (int)$val;
                                if($val === 1){
                                    $comp = 'less';
                                }elseif($val === 5){
                                    $comp = 'more';
                                }else{
                                    $comp = 'between';
                                }
                            }
    
                            $params[$key] = $val;
                        }
                        
                    }
                }
                
            }
            
            $priceProp = 'cprice';
            if($this->getUser()){
                if($this->getUser()->getType() && $this->getUser()->getType()->getId() === 2){
                    $priceProp = 'pricepro';
                }
            }

            if(isset($sortBy['order']) && isset($sortBy['sortBy'])){
                $needAddOrder = true;
                if($sortBy['sortBy'] !== "name"){
                    $sortBy['sortBy'] = $priceProp;
                }
            }

            if(!$needDql){
                if(isset($sortBy['order']) && isset($sortBy['sortBy'])){
                    $cat_products = $em->getRepository(Product::class)->findBy($params, [$sortBy['sortBy'] => $sortBy['order']], 12, $offset);
                }else{
                    
                    dump($sortBy);
                    $cat_products = $em->getRepository(Product::class)->findBy($params,  ['createdAt' => 'DESC'], 12, $offset);
                    dump($cat_products);
                }
            }else{
                $qb = $em->createQueryBuilder();
                $qb->select('p')->from('App\\Entity\\Product', 'p');
                $i = 0;
                dump($params);
                if(isset($params['granits'])){
                    $qb->leftJoin('p.granits', 'g');
                    //$qb
                    $qb->where('g.id = :granits');
                }
                foreach($params as $key => $value){
                    if($i === 0 && !isset($params['granits'])){
                        $qb->where('p.'.$key.' = :'.$key);
                    }
                    else{
                        if($key == 'granits'){
                            
                        }elseif($key == 'budget'){
                            if($comp == 'less'){
                                $qb->andWhere('p.'.$priceProp.' >= :'.$key);
                            }elseif($comp == 'more'){
                                $qb->andWhere('p.'.$priceProp.' <= :'.$key);
                            }else{
                                $qb->andWhere('p.'.$priceProp.' BETWEEN :min AND :max');
                            }
                            
                        }else{
                            $qb->andWhere('p.'.$key.' = :'.$key);
                        }
                        
                    } 

                    $i++;
                }

                foreach($params as $key => $value){
                    
                    if($key == 'granits'){
                        $qb->setParameter('granits', $value);
                    }elseif($key == 'budget'){
                        if($comp == 'less' || $comp == 'more'){
                            $qb->setParameter($key, $value);
                        }else{
                            $qb->setParameter('min', ($value*1000)-1000);
                            $qb->setParameter('max', ($value*1000));
                        }
                        
                    }else{
                        $qb->setParameter($key, $value);
                    }
                }
                if($needAddOrder){
                    $qb->orderBy('p.'.$sortBy['sortBy'], $sortBy['order']);
                }
                $qb->setFirstResult($offset)->setMaxResults(12);
                $query = $qb->getQuery();
                
                $cat_products = $query->getResult();
            }
             
        }
        dump($cat_products);
        if($request->isXmlHttpRequest()){
            return $this->render('category/infinity-scroll.html.twig', [
                'cat_product' => $cat_product, 'cat_products' => $cat_products, 'params' => $params, 'sortBy' => $sortBy
            ]);
        }

        $etape_desc = $em->getRepository(Page::class)->findOneBy(['slug' => 'etape_desc']);
        $testimonials = $em->getRepository(Testimonial::class)->findBy([], ['id' => 'DESC'], 10, 0);
        
        return $this->render('category/index.html.twig', [
            'cat_product' => $cat_product, 'cat_products' => $cat_products, 'params' => $params, 'sortBy' => $sortBy,
            'etape_desc' => $etape_desc, 'testimonials' => $testimonials,
        ]);
    }   

    /**
     * @Route("/articles/product/show/{id}", name="cat-prod-show")
     */
    public function show($id)
    {

        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->findOneBy(['id' => $id]);
        //dump($cat_product);
        //die();
        return $this->render('category/product.html.twig', [
            'prod' => $prod,
        ]);
    }
}
