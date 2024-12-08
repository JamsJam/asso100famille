<?php

namespace App\Service;

use phpDocumentor\Reflection\Types\Integer;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService
{
//     private $stripeSecretKey;

//     public function __construct(string $stripeSecretKey)
//     {
//         $this->stripeSecretKey = $stripeSecretKey;
//         Stripe::setApiKey($this->stripeSecretKey);
//     }

//     /**
//      * Créer une session de paiement pour un abonnement avec des frais ponctuels
//      *
//      * @param int $amountSubscription Montant de l'abonnement (en centimes)
//      * @param int $amountOneTime Montant des frais ponctuels (en centimes)
//      * @param string $currency Devise du paiement (ex: 'eur')
//      * @param string $successUrl URL de redirection en cas de succès
//      * @param string $cancelUrl URL de redirection en cas d'annulation
//      * @return Session
//      */
//     public function createCheckoutSession(
//         int $amountSubscription,
//         int $amountOneTime,
//         string $currency,
//         int $quantity,
//         string $successUrl,
//         string $cancelUrl
//     ): Session {
//         return Session::create([
//             'payment_method_types' => ['card'],
//             'line_items' => [
//                 [
//                     'price_data' => [
//                         'currency' => $currency,
//                         'product_data' => [
//                             'name' => 'Abonnement mensuel',
//                         ],
//                         'unit_amount' => $amountSubscription,
//                         'recurring' => [
//                             'interval' => 'month',
//                         ],
//                     ],
//                     'quantity' => $quantity,
//                 ],
//                 [
//                     'price_data' => [
//                         'currency' => $currency,
//                         'product_data' => [
//                             'name' => 'Frais de service',
//                         ],
//                         'unit_amount' => $amountOneTime,
//                     ],
//                     'quantity' => 1,
//                 ],
//             ],
//             'mode' => 'subscription',
//             'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
//             'cancel_url' => $cancelUrl,
//         ]);
//     }
}
