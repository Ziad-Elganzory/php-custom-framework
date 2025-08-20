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
            
            if ($email['meta'] === null) {
                echo "Skipping email ID {$email['id']}: meta field is null\n";
                continue;
            }
            
            $meta = json_decode($email['meta'], true);
            
            // Check if json_decode was successful
            if ($meta === null) {
                echo "Skipping email ID {$email['id']}: invalid JSON in meta field\n";
                continue;
            }

            $emailMessage = (new \Symfony\Component\Mime\Email())
                    ->to($meta['to'])
                    ->from($meta['from'])
                    ->subject($email['subject'])
                    ->text($email['text_body'])
                    ->html($email['html_body']);

            $this->mailer->send($emailMessage);

            $this->emailModel->markEmailSent($email['id']);
        
        }
    }
}