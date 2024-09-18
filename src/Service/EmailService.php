<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('noreply@tripsters.com') // Adresse e-mail de l'expéditeur
            ->to($to)                       // Adresse e-mail du destinataire
            ->subject($subject)              // Sujet de l'e-mail
            ->html($content);                // Contenu HTML de l'e-mail

        $this->mailer->send($email);         // Envoi de l'e-mail via Mailtrap
    }
}

