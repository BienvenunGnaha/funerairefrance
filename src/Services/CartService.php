<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Config;
use App\Entity\Devis;
use App\Entity\MoreInfo;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    
    private $em;

    private $projectDir;

    public function __construct(EntityManagerInterface $em, string $projectDir){
        $this->em = $em;
        $this->projectDir = $projectDir;
    }

    public function cartPrice(Cart $cart, ?User $user = null, $em){
        $isPro = false;
        $details = [];
        if($user){
            if($user->getType()){
                if($user->getType()->getId() === 2){
                    $isPro = true;
                }
            }
        }

        $tva = $em->getRepository(Config::class)->findOneBy(['confKey' => 'tva'])->getValue();//price_by_text
        $priceByText = $em->getRepository(Config::class)->findOneBy(['confKey' => 'price_by_text'])->getValue();
        $price = 0;
        $priceHT = 0;
        $shippingPrice = 0;
        $orders = $cart->getOrders();
        $lenOrders = count($orders);
        for($i = 0; $i < $lenOrders; $i++){
            $order = $orders[$i];
            $shipping = $order->getProductId()->getShCost();
            $price += $shipping;
            $details[(string)$order->getId()]['shipping'] = $shipping;
            $details[(string)$order->getId()]['product'] = $order->getProductId()->getName();
            $shippingPrice += $shipping;
            $tvaProd = ($order->getProductId()->getTva()) ? floatVal($order->getProductId()->getTva()->getValue()) : floatVal($tva);
            if($order->getProductId()->getSubCatId()->getCustomizableBeforeOrder() === true ){
                $meta = json_decode(json_decode($order->getMetaData(), true));
                dump($meta);
                dump($order->getQty());
                $objs = $meta->objects;
                $countObjs = count($objs);
                
                for($j = 0; $j < $countObjs; $j++){
                    $label = $objs[$j]->frfuLabelObject;
                    if($isPro){
                        $pp = (int)$objs[$j]->dataprice;
                        $qty = $order->getQty();
                        $ht = $pp*$qty;
                        $priceHT += $ht;
                        $ttc = $ht + ($ht*$tvaProd);
                        $price += $ttc;
                        $details[(string)$order->getId()]['list'][] = ['label' => $label, 'price' => $pp, 'qty' => $qty, 'ht' => $ht, 'ttc' => $ttc];
                    }else{
                        $pp = (int)$objs[$j]->dataprice;
                        $qty = $order->getQty();
                        $ttc = $pp*$qty;
                        $price += $ttc;
                        $details[(string)$order->getId()]['list'][] = ['label' => $label, 'price' => $pp, 'qty' => $qty, 'ht' => $ttc, 'ttc' => $ttc];
                        
                    }
                    
                }
                
                
            }else{

                if($isPro){
                    $pp = (int)$order->getProductId()->getPricepro();
                    $qty = $order->getQty();
                    $ht = $pp*$qty;
                    $priceHT += $ht;
                    $ttc = $ht + ($ht*$tvaProd);
                    $price += $ttc;
                    $details[(string)$order->getId()]['list'][] = ['label' => $order->getProductId()->getName(), 'price' => $pp, 'qty' => $qty, 'ht' => $ht, 'ttc' => $ttc];
                }else{
                    $pp = (int)$order->getProductId()->getCPrice();
                    $qty = $order->getQty();
                    $ttc = $pp*$qty;
                    $price += $ttc;
                    $details[(string)$order->getId()]['list'][] = ['label' => $order->getProductId()->getName(), 'price' => $pp, 'qty' => $qty, 'ht' => $ttc, 'ttc' => $ttc];
                }


                if($order->getProductId()->getNumberTextCustomize() > 0){
                    $meta = json_decode(json_decode($order->getMetaData(), true));
                    $objs = $meta->cus;
                    $countObjs = count($objs);
                    for($k = 0; $k < $countObjs; $k++){
                        if($isPro){
                            $pp = (int)$priceByText;
                            $qty = $order->getQty();
                            $ht = $pp*$qty;
                            $priceHT += $ht;
                            $ttc = $ht + ($ht*$tvaProd);
                            $price += $ttc;
                            $details[(string)$order->getId()]['list'][] = ['label' => 'Texte Personnalisé', 'price' => $pp, 'qty' => $qty, 'ht' => $ht, 'ttc' => $ttc];
                        }else{
                            $pp = (int)$priceByText;
                            $qty = $order->getQty();
                            $ttc = $pp*$qty;
                            $price += $ttc;
                            $details[(string)$order->getId()]['list'][] = ['label' => 'Texte Personnalisé', 'price' => $pp, 'qty' => $qty, 'ht' => $ht, 'ttc' => $ttc];
                        }
                    }
                }
                
            }
              
        }

        return ['total' => $price, 'totalHT' => $priceHT, 'shipping' => $shippingPrice, 'details' => $details];
    }

    public function updateStock(Cart $cart, $em){
        $orders = $cart->getOrders();
        $lenOrders = count($orders);
        for($i = 0; $i < $lenOrders; $i++){
            $order = $orders[$i];
            $product = $order->getProductId();
            $qty = $order->getQty();
            $stock = $order->getProductId()->getStock();
            $currentStock = $stock - $qty;
            if($currentStock <= 0){
                $product->setStatus(false);
            }
            $product->setStock($currentStock);
            $em->flush($product); 
        }
    }

    public function devis(Devis $devis, ?User $user=null, $em){
        dump('uydsfjhsdfjh');
        $isPro = false;
        if($user){
            if($user->getType()){
                if($user->getType()->getId() === 2){
                    $isPro = true;
                }
            }
        }
        $price = 0;
        $priceHT = 0;
        $details = [];
        $discount = 0;
        $meta = $devis->getMetaData();
        $objs = $meta['objects'];
        $countObjs = count($objs);
        $tva = $em->getRepository(Config::class)->findOneBy(['confKey' => 'tva'])->getValue();
        $tvaProd = ($devis->getProductId()->getTva()) ? floatVal($devis->getProductId()->getTva()->getValue()) : floatVal($tva);
        for($j = 0; $j < $countObjs; $j++){
            if($isPro){
                $ht = (int)$objs[$j]['dataprice'];
                $details[] = ['label' => $objs[$j]['frfuLabelObject'], 'price' => $ht];
                $priceHT += $ht;
                $price += $ht + ($ht*$tvaProd);
            }else{
                $details[] = ['label' => $objs[$j]['frfuLabelObject'], 'price' => (int)$objs[$j]['dataprice']];
                $price += (int)$objs[$j]['dataprice'];
            }
            
        }

        if($isPro){
            $ht = $devis->getGallery()->getPricepro();
            $details[] = ['label' => $devis->getProductId()->getName(), 'price' => $ht];
            $priceHT += $ht;
            $price += $ht + ($ht*$tvaProd);

            if ($devis->getOtherFees() !== null && $devis->getDiscount() !== null) {
                $priceHT = $priceHT + $devis->getOtherFees();
                $discount = ($priceHT*$devis->getDiscount())/100;
                $priceHT = $priceHT - $discount;
                $price = ($priceHT + ($priceHT*$tvaProd));
                
            }
        }else{
            $details[] = ['label' => $devis->getProductId()->getName(), 'price' => $devis->getGallery()->getPrice()];
            $price += $devis->getGallery()->getPrice();

            if ($devis->getOtherFees() !== null && $devis->getDiscount() !== null) {

                $price = $price + $devis->getOtherFees();
                $discount = ($price*$devis->getDiscount())/100;
                $price = $price - $discount;
                
            }
        }

        $dts = [
            'total' => $price,
            'totalHT' => $priceHT,
            'details' => $details,
            'discount' => $discount
        ];

        dump($dts);

        return $dts;
    }

    public function devisListImages(Devis $devis){
        $list = [$this->projectDir."/public".str_replace('https://funerairefrance.com', '',$devis->getSvg())];
        $meta = $devis->getMetaData();
        $objs = $meta['objects'];
        $countObjs = count($objs);
        $list[] = $this->projectDir."/public".str_replace('https://funerairefrance.com', '', $meta['backgroundImage']['src']);
        for($j = 0; $j < $countObjs; $j++){
            if($objs[$j]['type'] == 'image'){
                $list[] = $this->projectDir."/public".str_replace('https://funerairefrance.com', '',$objs[$j]['src']);
            }
        }

        $moreInfo = $this->em->getRepository(MoreInfo::class)->findOneBy(['devis' => $devis->getId()]);

        if($moreInfo instanceof MoreInfo){
            if($moreInfo->getActeConcession() !== null){
                $list[] = $this->projectDir."/public/".$moreInfo->getActeConcession();
            }

            $photoConcession = $moreInfo->getPhotoConcession();
            if($photoConcession !== null){
                for($i = 0; $i < count($photoConcession); $i++){
                    $list[] = $this->projectDir."/public/".$photoConcession[$i];
                }
            }
        }

        return $list;
    }

    public function cartListImages(Cart $cart){
        $list = [];
    
        $orders = $cart->getOrders();
        $lenOrders = count($orders);
        for($i = 0; $i < $lenOrders; $i++){
            $order = $orders[$i];
            
            if($order->getProductId()->getSubCatId()->getCustomizableBeforeOrder() === true ){
                $meta = json_decode(json_decode($order->getMetaData(), true));
                $objs = $meta->objects;
                $countObjs = count($objs);//backgroundImage
                if($order->getInvoice() !==  null){
                    $list[] = $this->projectDir."/public".str_replace('https://funerairefrance.com', '', $order->getInvoice()); 
                }
                for($j = 0; $j < $countObjs; $j++){
                    $label = $objs[$j]->frfuLabelObject;
                    if($objs[$j]->type == 'image'){
                        $list[] = $this->projectDir."/public".str_replace('https://funerairefrance.com', '',$objs[$j]->src); 
                    }
                }
                
                
            }
              
        }

        return $list;
    }

    
}