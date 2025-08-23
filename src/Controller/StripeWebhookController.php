<?php
namespace App\Controller;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Service\MailerService;
use App\Service\StripeService;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeWebhookController extends AbstractController
{

    public function __construct(
        private string $webhookSign,
        private StripeService $stripeService,
        private UserRepository $ur
    )
    {}

    #[Route('/stripe/webhook', name: 'app_stripe_webhook', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MailerService $mailerService,
        ): Response
    {
        $stripeClient = $this->stripeService->getStripeClient();
        $payload = $request->getContent();
        $sig_header = $request->headers->get('stripe-signature');
        $endpoint_secret = $this->webhookSign; 


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
                $invoice = $event->data->object; // \Stripe\Invoice
                $invoiceUrl = $invoice->hosted_invoice_url; // lien cliquable
                $customerEmail = $invoice->customer_email;

                if ($customerEmail && $invoiceUrl) {
                    $invoiceMail = [
                        'from' => new Address("contact@tiers-lieu100p100famille.fr", "Association 100% Famille"),
                        'to' => $customerEmail,
                        'template' => 'emails/send_paiement_invoice.html.twig',
                        'subject' => 'Votre facture - Association 100% famille',
                        'context' => [
                            "invoiceURL" => $invoiceUrl
                        ],
                    ];

                    $mailerService->sendTemplatedMail(
                        $invoiceMail["from"],
                        $invoiceMail["to"],
                        $invoiceMail["subject"],
                        $invoiceMail["template"],
                        $invoiceMail["context"]
                    );
                }
                break;

            case 'checkout.session.completed':

                
                // TODO : recupéré la facture
                $session = $event->data->object;
                $userMail = $session->customer_email;
                $invoice = null;
                if (empty($session->invoice)) {
                    $customerId = $session->customer; 
                    $invoice = \Stripe\Invoice::create([
                        'customer' => $customerId,
                        'collection_method' => 'send_invoice',
                        'auto_advance' => true,
                    ]);
                    $invoice->finalizeInvoice();
                } else {
                    $invoice = $stripeClient->invoices->retrieve($session->invoice);
                    
                }
                $invoiceUrl = $invoice->hosted_invoice_url;
                $invoiceMail = [
                    'from' => new Address("contact@tiers-lieu100p100famille.fr","Association 100% Famille"),
                    'to'  => $userMail,// email du user
                    'template' => 'emails/send_paiement_invoice.html.twig',
                    'subject'  => 'Votre facture - Association 100% famille',
                    'context'  => [
                        "invoiceURL" => $invoiceUrl
                    ], 
                ];
                
                // TODO :  envoyer un mail
                $mailerService->sendTemplatedMail(
                    $invoiceMail["from"],
                    $invoiceMail["to"],
                    $invoiceMail["subject"],
                    $invoiceMail["template"],
                    $invoiceMail["context"],
                ) ;

                break;
            case 'invoice.payment_failed':
                $invoice = $event->data->object; // \Stripe\Invoice
                $invoiceUrl = $invoice->hosted_invoice_url;
                $customerEmail = $invoice->customer_email;

                if ($customerEmail && $invoiceUrl) {
                    $failedMail = [
                        'from' => new Address("contact@tiers-lieu100p100famille.fr", "Association 100% Famille"),
                        'to' => $customerEmail,
                        'template' => 'emails/payment_failed.html.twig',
                        'subject' => 'Échec de votre paiement - Association 100% Famille',
                        'context' => [
                            "invoiceURL" => $invoiceUrl,
                            "amount_due" => $invoice->amount_due / 100, // montant à payer en €
                            "due_date" => $invoice->due_date ? date('d/m/Y', $invoice->due_date) : null,
                        ],
                    ];

                    $mailerService->sendTemplatedMail(
                        $failedMail["from"],
                        $failedMail["to"],
                        $failedMail["subject"],
                        $failedMail["template"],
                        $failedMail["context"]
                    );
                }
                break;
        }

        return new Response('OK', 200);
    }

    // private function sendInvoiceByEmail(string $email, ?string $invoiceUrl): void
    // {
    //     if (!$email || !$invoiceUrl) return;

    //     // Exemple simple avec MailerInterface
    //     $emailMessage = (new \Symfony\Component\Mime\Email())
    //         ->from('no-reply@tonsite.com')
    //         ->to($email)
    //         ->subject('Votre facture Stripe')
    //         ->text("Bonjour,\n\nVous pouvez télécharger votre facture ici : $invoiceUrl");

    //     // $mailer = new M(\Symfony\Component\Mailer\Transport::fromDsn($_ENV['MAILER_DSN']));
    //     // $mailer->send($emailMessage);
    // }
}