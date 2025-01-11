<?php
namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    function __construct(
        public MailerInterface $mailer,
    )
    {}


    public function sendTemplatedMail(
        string|Address $from,
        string|Address $to,
        string $subject,
        string $template,
        array $context

    ){
        $email = (new TemplatedEmail())
                    ->from($from)
                    ->to($to)
                    ->subject($subject . " - Association 100% Famille")
                    ->htmlTemplate($template)
                    ->context($context)
                ;

                $this->mailer->send($email);
    }
}