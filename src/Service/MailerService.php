<?php
namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Psr\Log\LoggerInterface;



class MailerService
{
    function __construct(
        public MailerInterface $mailer,
        private LoggerInterface $logger
    )
    {}


    public function sendTemplatedMail(
        string|Address $from,
        string|Address $to,
        string $subject,
        string $template,
        array $context

    ){
        try {
            $email = (new TemplatedEmail())
                        ->from($from)
                        ->to($to)
                        ->subject($subject . " - Association 100% Famille")
                        ->htmlTemplate($template)
                        ->context($context)
                    ;

                    $this->mailer->send($email);
                    $this->logger->info("Email sent successfully to {$to}", ['subject' => $subject]);
        
        } catch (\Throwable $exception) {
            $this->logger->error("Failed to send email: " . $exception->getMessage(), [
                'from' => $from,
                'to' => $to,
                'subject' => $subject,
                'template' => $template,
                'context' => $context,
            ]);

            throw new \RuntimeException('Failed to send email: ' . $exception->getMessage());
        }
    }
}