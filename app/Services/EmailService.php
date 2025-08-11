<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Email;
use App\Enums\EmailStatus;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(protected Email $emailModel,protected MailerInterface $mailer){}
    public function sendQueuedEmails()
    {        
        $emails = $this->emailModel->getEmailsByStatus(EmailStatus::Queue);

        foreach($emails as $email)
        {
            $meta = json_decode($email->meta,true);

            $emailMessage = (new \Symfony\Component\Mime\Email())
                    ->to($meta['from'])
                    ->from($meta['to'])
                    ->subject($email->subject)
                    ->text($email->text_body)
                    ->html($email->html_body);

            $this->mailer->send($emailMessage);

            $this->emailModel->markEmailSent($email->id);
        
        }
    }
}