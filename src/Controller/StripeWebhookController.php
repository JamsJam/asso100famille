<?php
namespace App\Controller;

use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeWebhookController extends AbstractController
{

    public function __construct(
        private string $webhookSign
    )
    {}

    #[Route('/stripe/webhook', name: 'app_stripe_webhook', methods: ['POST'])]
    public function __invoke(
        Request $request,
        ): Response
    {
        $payload = $request->getContent();
        $sig_header = $request->headers->get('stripe-signature');
        $endpoint_secret = $this->webhookSign; // récupéré dans Stripe
        // $endpoint_secret = $_ENV['STRIPE_WEBHOOK_SECRET']; // récupéré dans Stripe

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            return new Response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return new Response('Invalid signature', 400);
        }

        // Gestion des événements
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $pdfUrl = $invoice->invoice_pdf;
                $invoiceNumber = $invoice->number;
                $customerId = $invoice->customer;

                // TODO : retrouver l'utilisateur via $customerId et envoyer le mail
                break;

            case 'invoice.payment_failed':
                // TODO : avertir le client ou suspendre
                break;
            case 'checkout.session.completed':
                // TODO : recupéré la facture et envoyer un mail
                break;
            case 'checkout.session.failed':
                // TODO : avertir le client ou suspendre
                break;
        }

        return new Response('OK', 200);
    }
}