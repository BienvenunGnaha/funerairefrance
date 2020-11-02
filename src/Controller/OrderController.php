<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Cart;
use App\Entity\Devis;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\Config;
use App\Entity\Status;
use App\Entity\EmailTemplate;
use App\Form\RegistrationFormType;
use App\Form\ShippingType;
use App\Form\AddressType;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\PaymentIntent;
use App\Services\SendMail;
use App\Services\CartService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;

class OrderController extends AbstractController
{
    

    /**
     * @Route("/checkout/cart", name="cart")
     */
    public function index(Request $request)
    {

        $cookie = $request->cookies->get('frfu_crt_id');
        $em = $this->getDoctrine()->getManager();
        $cartCookie = null;

        if($this->getUser()){
            $cartCookie = $em->getRepository(Cart::class)->findOneCartNoPaid($this->getUser());
            
            if(!$cartCookie){
                
                $cartCookie = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $cookie]);
                if($cartCookie && !$cartCookie->getUser()){
                    
                    $user = $this->getUser();
                    $cartCookie->setUser($user);
                    $em->flush($cartCookie);
                }
            }
        }
        else{
            $cartCookie = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $cookie]);
        }

        //dump($cartCookie->getOrders()[0]->getProductId());

        return $this->render('order/index.html.twig', [
            'cart' => $cartCookie
        ]);
    }

    /**
     * @Route("/user/cart", name="carts-user")
     */
    public function userCart(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $carts = $em->getRepository(Cart::class)->findBy(['user' => $this->getUser()->getId()]);
        return $this->render('order/user-cart.html.twig', ['carts' => $carts, 'devises' => $em->getRepository(Devis::class)->findBy(['user' => $this->getUser()->getId()])]);
    }

    /**
     * @Route("/user/cart/{uniqId}", name="cart-user-view")
     */
    public function cart(Request $request, $uniqId)
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $uniqId]);
        return $this->render('order/user-cart-view.html.twig', ['cart' => $cart]);
    }

    /**
     * @Route("/user/cart/invoice/{id}", name="carts-invoice-pdf")
     */
    public function invoice(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository(Cart::class)->find($id);
        if($cart){
            $tvaConf = $em->getRepository(Config::class)->findOneBy(['confKey' => 'tva']);
            
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            
            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);
            
            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('order/invoice-pdf.html.twig', [
                'cart' => $cart, 'tva' => floatval($tvaConf->getValue())
            ]);
            
            // Load HTML to Dompdf
            $dompdf->loadHtml($html);
            
            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();
                //die();
            // Store PDF Binary Data
            $output = $dompdf->output();
            $dompdf->stream("facture_france_funéraire.pdf", [
                    "Attachment" => true
                ]);
            dump($dompdf);
            die();
            
        }
        //return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Route("/checkout/cart/{idOrder}/update/qty/{number}", name="cart-update-qty")
     */
    public function updateQty(Request $request, $idOrder, $number)
    {

        if($idOrder && (int)$idOrder !== 0 && $number && (int)$number > 0){
            $em = $this->getDoctrine()->getManager();
            $order = $em->getRepository(Order::class)->findOneBy(['id' => $idOrder]);
            if($order){
                $order->setQty($number);
                $em->flush();
                return $this->json([], 200);
            }
        }

        return $this->json([], 500);
    }

    /**
     * @Route("/checkout/cart/order/delete/{idOrder}", name="cart-order-remove")
     */
    public function removeOrder(Request $request, $idOrder)
    {

        if($idOrder && (int)$idOrder !== 0){
            $em = $this->getDoctrine()->getManager();
            $order = $em->getRepository(Order::class)->findOneBy(['id' => $idOrder]);
            if($order){
                $order->setUserId(null);
                $em->flush();
                $em->remove($order);
                $em->flush();
                return $this->json([], 200);
            }
        }

        return $this->json([], 500);
    }

    /**
     * @Route("/checkout/cart/delete/{id}", name="cart-remove")
     */
    public function removeCart(Request $request, $id)
    {

        if($id && (int)$id !== 0){
            $em = $this->getDoctrine()->getManager();
            $cart = $em->getRepository(Cart::class)->findOneBy(['id' => $id]);
            if($cart){
                //try{
                    /*$orders = $cart->getOrders();
                    $len = count($orders);

                    for($i = 0; $i < $len; $i++){
                        
                        $orders[$i]->setUserId(null);
                        $em->flush();
                    }*/

                    $cart->setUser(null);
                    $em->flush();
                
                    $em->remove($cart);
                    $em->flush();
                /*}
                catch(\Exception $e){
                    return $this->json([], 500); 
                }*/
                
                return $this->json([], 200);
            }
        }

        return $this->json([], 500);
    }

    /**
     * @Route("/checkout/cart/auth", name="checkout-cart-auth-user")
     */
    public function checkoutAuth(Request $request){
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        setcookie('r_url', $this->generateUrl('checkout-cart'), time() + 80000, '/', 'funerairefrance.com', true, true);
        $cookie = $request->cookies->get('frfu_crt_id');
        $cartCookie = null;
        if($this->getUser()){
            $cartCookie = $em->getRepository(Cart::class)->findOneCartNoPaid($this->getUser());
            
        }
        else{
            $cartCookie = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $cookie]);
        }
        return $this->render('order/checkout-login.html.twig', [
            'registrationForm' => $form->createView(), 'cart' => $cartCookie
        ]);
    }

    /**
     * @Route("/user/checkout/cart", name="checkout-cart")
     */
    public function checkout(Request $request){
        $user = $this->getUser();
        $address = new Address();
        $address->setUserId($user);
        $formUser = $this->createForm(ShippingType::class, $user);
        $formAddress = $this->createForm(AddressType::class, $address);
        ///$formUser->handleRequest($request);
        $strCus = $user->getStripeCustomer();
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository(Config::class)->findOneBy(['confKey' => 'stripe_sk']);
        Stripe::setApiKey($config->getValue());
        if($strCus === null){
            

            $cus = Customer::create([
                'name' => $user->getFirstName().' '.$user->getName(),
                'email' => $user->getEmail()
            ]);
            $user->setStripeCustomer($cus->id);
            $em->flush($user);
        }

        $cookie = $request->cookies->get('frfu_crt_id');
        $cartCookie = $em->getRepository(Cart::class)->findOneCartNoPaid($this->getUser());
        
        if(!$cartCookie){
            
            $cartCookie = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $cookie]);
            if($cartCookie && !$cartCookie->getUser()){
                
                $user = $this->getUser();
                $cartCookie->setUser($user);
                $em->flush($cartCookie);
            }elseif($cartCookie && $cartCookie->getUser()){
                $cartCookie = null;
            }
        }
        
        if(!$cartCookie){
            $this->addFlash(
                'danger',
                'Le panier auquel vous désire procéder au paiement appartient à un autre utlisateur!'
            );
            return $this->redirect('/checkout/cart');
        }
        $setupIntent = SetupIntent::create([ 'customer' => $user->getStripeCustomer()]);


        return $this->render('order/checkout.html.twig', [ 'cart' => $cartCookie,
            'formUser' => $formUser->createView(), 'formAddress' =>  $formAddress->createView(), 'clientSecret' => $setupIntent->client_secret
        ]);
    }

    /**
     * @Route("/user/checkout/cart/user/info", name="checkout-cart-user-info")
     */
    public function checkoutShippingUserInfo(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(ShippingType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->json([], 200);
        }

        return $this->json([], 500);
    }

    /**
     * @Route("/user/checkout/cart/shipping/address", name="checkout-cart-shipping-address")
     */
    public function checkoutShippingAddress(Request $request){
        $user = $this->getUser();
        $address = new Address();
        $address->setUserId($user);
        $formUser = $this->createForm(ShippingType::class, $user);
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $address->setDefault(false);
            $em->persist($address);
            $em->flush();
            $addresses = $em->getRepository(Address::class)->findBy(['userId' => $this->getUser()]);
            $count = count($addresses);
            for ($i=0; $i < $count; $i++) { 
                $ad = $addresses[$i];
                $ad->setByDefault(false);
                $em->flush($ad);
            }  
            $address->setByDefault(true);
            $em->flush($address); 
            $cookie = $request->cookies->get('frfu_crt_id');
            $cartCookie = $em->getRepository(Cart::class)->findOneBy(['uniqId' => $cookie]);
            if($cartCookie === null && $this->getUser()){
                $carts = $em->getRepository(Cart::class)->findAll();
                $len = count($carts);
                for($i = 0; $i < $len; $i++){
                    if($carts[$i]->getUser() instanceof User){
                        if($carts[$i]->getUser()->getId() === $this->getUser()->getId()){
                            $cartCookie = $carts[$i];
                        }
                    }
                }
            }
            if($cartCookie){
                $cartCookie->setAddress($address);
                
                $em->flush($cartCookie);
            }
           return $this->json([], 200);
        }

        return $this->json([], 500);
    }

    private function updateCartAddress(Address $address, Request $request){
       
    }

    /**
     * @Route("/user/checkout/cart/stripe", name="checkout-cart-stripe")
     */
    public function checkoutStripe(Request $request, SendMail $mailer, CartService $cartService){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $token = $request->request->get('_token');
        $pm = $request->request->get('pm');

        if ($this->isCsrfTokenValid('pay_form_token', $token) && $pm && is_string($pm)) {
            
            $stripe_secret = $em->getRepository(Config::class)->findOneBy(['confKey' => 'stripe_sk']);
            $cart = $em->getRepository(Cart::class)->findOneCartNoPaid($this->getUser());
            
            $tva = $em->getRepository(Config::class)->findOneBy(['confKey' => 'tva'])->getValue();
            
            Stripe::setApiKey($stripe_secret->getValue());
            
            $isPro = false;
            if($user->getType()){
                if($user->getType()->getId() === 2){
                    $isPro = true;
                }
            }
            if(!$user->getStripeCustomer()){
                

                $cus = Customer::create([
                    'name' => $user->getFirstName().' '.$user->getName(),
                    'email' => $user->getEmail()
                ]);

                $user->setStripeCustomer($cus->id);
            }
            //return $this->json([], 200);
            $price = 0;
            $prices = [];
            //return $this->json([], 200);
            if($cart){
                if(!$cart->getAddress()){
                    $address = $em->getRepository(Address::class)->findOneBy(['userId' => $user->getId(), 'byDefault' => true]);
                    $cart->setAddress($address);
                }
                $pricing = $cartService->cartPrice($cart, $user, $em);
                
                try {
                    
                    if($pricing['total'] > 0){
                        
                        
                        $pi = PaymentIntent::create([
                            'amount' => $pricing['total']*100,
                            'currency' => 'eur',
                            'customer' => $user->getStripeCustomer(),
                            'payment_method' => $pm,
                            'off_session' => true,
                            'confirm' => true,
                        ]);
                        

                        if($pi->status === 'succeeded'){ 
                            $cartService->updateStock($cart, $em); 
                            $cart->setPaid(true);
                            $cart->setStatus($em->getRepository(Status::class)->find(2));
                            $em->flush($cart);
                            $pdfOptions = new Options();
                            $pdfOptions->set('defaultFont', 'Arial');
                            
                            // Instantiate Dompdf with our options
                            $dompdf = new Dompdf($pdfOptions);
                            
                            // Retrieve the HTML generated in our twig file
                            $html = $this->renderView('order/invoice-order-pdf.html.twig', [
                                'cart' => $cart, 'tva' => floatVal($tva)
                            ]);
                            
                            // Load HTML to Dompdf
                            $dompdf->loadHtml($html);
                            
                            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
                            $dompdf->setPaper('A4', 'portrait');

                            // Render the HTML as PDF
                            $dompdf->render();

                            // Store PDF Binary Data
                            $output = $dompdf->output();
                            
                            // In this case, we want to write the file in the public directory
                            $directory = '/invoice';
                            $publicDirectory = '../public/invoice';
                            $filename = $directory.'/facture_commande_funerairefrance_'.$cart->getUniqId().'.pdf';
                            $pdfFilepath =  $publicDirectory .'/facture_commande_funerairefrance_'.$cart->getUniqId().'.pdf';
                            $first = $user->getFirstName();
                            $name = $user->getName();
                            
                            // Write file to the desired path
                            file_put_contents($pdfFilepath, $output);

                            $contentMail = $this->getDoctrine()->getManager()->getRepository(EmailTemplate::class)->findOneBy(['slug' => 'order_payed']);
                            $content = str_replace([ '::name::', '::link::'], 
                            [$first.' '.$name, 'https://funerairefrance.com'.$filename], $contentMail->getContent());
                            $data = [
                                'to' => $user->getEmail(),
                                'body' => $content,
                                'subject' => 'Commande et Paiement acceptés'
                            ];

                            $mailer->sendWithAttachement($data, [$filename]);
                            setcookie('frfu_crt_id', '', time() - 3600, '/', 'funerairefrance.com', true, true);
                            return $this->json(['pi' => $prices, 'price' => $price], 200);
                        }
                        
                    }else{
                            return $this->json([], 500);
                    }
                } catch (\Stripe\Exception\CardException $e) {
                    // Error code will be authentication_required if authentication is needed
                    return $this->json(array('unsuccess' => $e->getMessage()), 500);
                }
            }

        }


        return $this->json([], 500);
    }

    /**
     * @Route("/user/checkout/cart/paypal", name="checkout-cart-paypal")
     */
    public function checkoutPaypal(Request $request){
        $user = $this->getUser();
        $address = new Address();
        $address->setUserId($user);
        $formUser = $this->createForm(ShippingType::class, $user);
        $formAddress = $this->createForm(AddressType::class, $adress);
        ///$formUser->handleRequest($request);


        return $this->render('order/checkout.html.twig', [
            'formUser' => $formUser->createView(), 'formAddress' =>  $formAddress
        ]);
    }

    /**
     * @Route("/user/checkout/cart/cheque", name="checkout-cart-cheque")
     */
    public function checkoutCheque(Request $request){
        $user = $this->getUser();
        $address = new Address();
        $address->setUserId($user);
        $formUser = $this->createForm(ShippingType::class, $user);
        $formAddress = $this->createForm(AddressType::class, $adress);
        ///$formUser->handleRequest($request);


        return $this->render('order/checkout.html.twig', [
            'formUser' => $formUser->createView(), 'formAddress' =>  $formAddress
        ]);
    }
}
