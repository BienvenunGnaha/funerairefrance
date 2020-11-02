<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Subcategory;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class SubcategoryController extends AbstractController
{
    /**
     * @Route("/subcategory", name="subcategory")
     */
    public function index()
    {
        return $this->render('subcategory/index.html.twig', [
            'controller_name' => 'SubcategoryController',
        ]);
    }

    /**
     * @Route("/subcat/{id}/product", name="subcat-product")
     */
    public function product(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $offset = (int)$request->query->get('offset') ? (int)$request->query->get('offset') : 0;
        $subcat = $em->getRepository(Subcategory::class)->findOneBy(['id' => $id]);
        $products = $em->getRepository(Product::class)->findBy(['subCatId' => $id], ['createdAt' => 'DESC'], 12, $offset);
        $queries = $request->query->all();
        $params = ['subCatId' => $id];
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
                    $products = $em->getRepository(Product::class)->findBy($params, [$sortBy['sortBy'] => $sortBy['order']], 12, $offset);
                }else{
                    
                    dump($sortBy);
                    $products = $em->getRepository(Product::class)->findBy($params, ['createdAt' => 'DESC'], 12, $offset);
                    
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
                dump($query);
                $products = $query->getResult();
            }

            if($request->isXmlHttpRequest()){
                return $this->render('subcategory/infinity-scroll.html.twig', [
                    'subcat' => $subcat, 'subCatProducts' => $products, 'params' => $params, 'sortBy' => $sortBy
                ]);
            }
             
        }
        return $this->render('subcategory/product.html.twig', [
            'subcat' => $subcat, 'subCatProducts' => $products, 'params' => $params, 'sortBy' => $sortBy
        ]);
    }

    
}
