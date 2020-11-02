<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Devis;
use App\Entity\Config;
use Stripe\Event;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Plan;
use Stripe\Exception\CardException;
use App\Services\SendMail;

class DevisController extends AbstractController
{
    /**
     * @Route("/user/devis", name="user-devis")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $devises = $em->getRepository(Devis::class)->findBy(['user' => $this->getUser()->getId()]);
        
        return $this->render('devis/index.html.twig', [
            'devises' => $devises,
        ]);
    }

    /**
     * @Route("devis/subs/hook", name="devis-subs-hooks")
     */
    public function hook(SendMail $mailer){
        sleep(2);
        $em = $this->getDoctrine()->getManager();
        $payload = @file_get_contents('php://input');
        $event = null;
        $donne = json_decode($payload, true);
        try {
              $event = Event::constructFrom($donne);
        } catch(\UnexpectedValueException $e) {
                 // Invalid payload
                 http_response_code(400);
                 exit();
        }
        $d = date('Y-m-d', time()+(3600*24*30));
// Handle the event
    switch ($event->type) {
        
        case 'invoice.payment_succeeded':
              $success = $event->data->object;
             $devis = $em->getRepository(Devis::class)->findOneBy(['subscription' => $success->subscription ]);
             $cycle = $devis->getCycle();
             $devis->setCycle($cycle-1);
             $em->flush();

             if($devis->getCycle() === 0){

                $subscription_d = Subscription::retrieve(
                    $devis->getSubscription()
                  );
                  $subscription_d->delete();
                $mailer->send(['to' => $devis->getEmail(), 
             'subject' => 'Dernier Prélèvement Mensuel du devis N° '.$devis->getId(),
              'body' => '<p>Bonjour '.$devis->getFirstName().'</p>Nous sommes heureux de vous informer que le prélèvement pour votre devis est terminé<p>.
              Merci pour la confiance placée en Funéraire France</p> <p>Merci</p>']);
             }else{
                $mailer->send(['to' => $devis->getEmail(), 
             'subject' => 'Paiement Mensuel',
              'body' => '<p>Bonjour '.$devis->getFirstName().'</p>Un nouveau prélèvement mensuel a été effectué pour votre devis N° '.$devis->getId().'<p>.
              Merci pour la confiance placée en Funéraire France</p> <p>Merci</p>']);
             }
             
        break;
        case 'invoice.payment_failed':
        $failure = $event->data->object; // contains a \Stripe\PaymentMethod
        $devis = $em->getRepository(Devis::class)->findOneBy(['subscription' => $failure->subscription ]);
        $devis->setRequiredUpdatedPm(true);
        $em->flush();
        $mailer->send(['to' => $devis->getEmail(), 
        'subject' => 'Echec du prélèvement Mensuel',
         'body' => '<p>Bonjour '.$devis->getFirstName().'</p>Le prélèvement mensuel pour votre devis N° '.$devis->getId().' a échoué. Veuilez vous connecter sur votre dashboard pour mettre à jour votre moyen de paiement.<p>.
         Merci pour la confiance placée en Funéraire France</p> <p>A très bientot sur Funeraire france</p>']);
        break;
         // ... handle other event types
       default:
        // Unexpected event type
        http_response_code(400);
        exit();
    }

    
        return $this->json(array('success' => true, 'date' => $d), 200);
    }

    /**
     * @Route("/devis/{id}/update/payment", name="devis-update-payment")
     */
    public function updatePayment(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $devis = $em->getRepository(Devis::class)->find($id);
        $submittedToken = $request->request->get('_token');
        $stripeSecret = $em->getRepository(Config::class)->findOneBy(['confKey' => 'stripe_sk']);
        Stripe::setApiKey($stripeSecret->getValue());

        // 'delete-item' is the same value used in the template to generate the token
        if ($this->isCsrfTokenValid('devis-update-payment', $submittedToken)) {
            $devis->setPm($request->request->get('pm'));
            $em->flush($devis);
            $isPro = false;
            if($user->getType()){
                if($user->getType()->getId() === 2){
                    $isPro = true;
                }
            }
            

            try {
                Customer::update(
                $user->getStripeCustomer(),
                ['invoice_settings' => ['default_payment_method' => $devis->getPm()]]
                );
            } catch (CardException $e) {
                    // Error code will be authentication_required if authentication is needed
                    return $this->redirect($request->headers->get('referer'));
            }

            $subscription_d = Subscription::retrieve(
                $devis->getSubscription()
            );
            $subscription_d->delete();

                $subscription = Subscription::create([
                    'customer' => $user->getStripeCustomer(),
                    'items' => [['plan' => $devis->getPlan()]],
                    'off_session' => TRUE
                ]);

                if($subscription->status == 'active'){
                    $devis->setRequiredUpdatePm(false);
                }

                $devis->setSubscription($subscription->id);
                $em->flush($devis);
        }
        
        

        return $this->redirect($request->headers->get('referer'));
    }
}
