<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Entity\Product;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class WishlistController extends AbstractController
{
    /**
     * @Route("/user/wishlist", name="wishlist")
     */
    public function index()
    {
        $user = $this->getUser();
        $wishlist = $user->getWishlist();
        $em = $this->getDoctrine()->getManager();
        if(!$wishlist){
            $wishlist = new Wishlist();
            $em->persist($wishlist);
            $em->flush();

            $user->setWishlist($wishlist);
            $em->flush($user);
        }

        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * @Route("/user/wishlist/add/product/{id}", name="wishlist-add-product")
     */
    public function addProduct(Request $request, $id)
    {
        $user = $this->getUser();
        $wishlist = $user->getWishlist();
        $em = $this->getDoctrine()->getManager();
        try{
            if(!$wishlist){
                $wishlist = new Wishlist();
                $em->persist($wishlist);
                $em->flush();
    
                $user->setWishlist($wishlist);
                $em->flush($user);
            }
    
            $prod = $em->getRepository(Product::class)->find($id);
            $wishlist->addProduct($prod);
            $em->flush($wishlist);
        }
        catch(\Exception $e){
            return $this->json([], 500);
        }
        
        
        return $this->json(['success' => true], 200);
    }

    /**
     * @Route("/user/wishlist/remove/product/{id}", name="wishlist-rm-product")
     */
    public function removeProduct(Request $request, $id)
    {
        $user = $this->getUser();
        $wishlist = $user->getWishlist();
        $em = $this->getDoctrine()->getManager();
        try{
            if($wishlist){
                $prod = $em->getRepository(Product::class)->find($id);
                $wishlist->removeProduct($prod);
                $em->flush($wishlist);
                return $this->json(['success' => true], 200);
            }
    
            
        }
        catch(\Exception $e){
            return $this->json([], 500);
        }
        
        return $this->json([], 500);
        
    }
}
