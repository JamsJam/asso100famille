<?php

namespace App\Service;

use Stripe\StripeClient;
use Stripe\Checkout\Session;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeService
{



    public function __construct(
        private string $stripeSecretKey,
        private UrlGeneratorInterface $urlGenerator,
    ){}

    /**
     * Créer une session Stripe Checkout et retourner l'URL
     *
     * @param array  $products Tableau des produits avec type ('one_time' ou 'subscription')
     * @param string $successUrl url apres succes du paiement
     * @param string $cancelUrl url apres echec du paiement
     * @return string
     */
    public function createCheckoutSession(
        array $products,
        ?string $successUrl = null,
        ?string $cancelUrl = null
    ): string 
    {


        $stripe = new StripeClient($this->stripeSecretKey);

        //? ================= set Default Parameters
            $lineItem = [];
            $mode = 'payment'; // Par défaut, mode pour les paiements uniques
            // default sucess and cancel
            $successRedirect = $successUrl || $this->urlGenerator->generate("app_stripe_success",[],UrlGeneratorInterface::ABSOLUTE_URL);
            $cancelRedirect  = $cancelUrl || $this->urlGenerator->generate("app_stripe_cancel",[],UrlGeneratorInterface::ABSOLUTE_URL);

        foreach ($products as $product) {
        //? ================= Error Checking
                    // Vérifier que le tableau de produits n'est pas vide
            if (empty($products)) {
                throw new \InvalidArgumentException('Le tableau de produits ne peut pas être vide.');
            }
            if (!isset($product['productName'], $product['amount'], $product['quantity'], $product['type'])) {
                throw new \InvalidArgumentException(
                    'Chaque produit doit contenir "productName", "amount", "quantity", et "type".'
                );
            }

        //? ================= Products handle
            if ($product['type'] === 'subscription') {
                $mode = 'subscription';
                $lineItem[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'recurring' => [
                            'interval' => $product['interval'], // Ex : 'month', 'year'
                        ],
                        'product_data' => [
                            'name' => $product['productName'],
                        ],
                        'unit_amount' => $product['amount'], // Montant en centimes
                    ],
                    'quantity' => $product['quantity'],
                ];
            } else { // Si c'est un produit standard (paiement unique)
                $lineItem[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $product['productName'],
                        ],
                        'unit_amount' => $product['amount'], // Montant en centimes
                    ],
                    'quantity' => $product['quantity'],
                ];
            } }


        //? =========== Create  session
            try{
                $checkout_session = $stripe->checkout->sessions->create([
                    'payment_method_types' => ['card'],
                    'line_items' => $lineItem,
                    'mode' => $mode,
                    'success_url' => $successRedirect,
                    'cancel_url' => $cancelRedirect,
                ]);
            } catch (\Exception $e) {
                throw new \RuntimeException('Erreur Stripe : ' . $e->getMessage());
            }

        
        
            return $checkout_session->url;
    }


}
