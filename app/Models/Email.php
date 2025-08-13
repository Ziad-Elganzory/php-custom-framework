<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use Symfony\Component\Mime\Address;

class Email extends Model
{
    public function queue(
        Address $to,
        Address $from,
        string $subject,
        string $html,
        ?string $text = null
    )   {
        $stmt = $this->db->createQueryBuilder()
            ->insert('emails')
            ->values([
                'subject' => ':subject',
                'status' => ':status',
                'text_body' => ':text_body',
                'html_body' => ':html_body',
                'meta' => ':meta',
                'created_at' => ':created_at',
            ]);

        $meta['to'] = $to->toString();
        $meta['from'] = $from->toString();

        $stmt->setParameters([
            'subject' => $subject,
            'status' => EmailStatus::Queue->value,
            'text_body' => $text,
            'html_body' => $html,
            'meta' => json_encode($meta),
            'created_at' => date('Y-m-d H:i:s'),
        ])->executeStatement();
        return (int) $this->db->lastInsertId();
    }

    public function getEmailsByStatus(EmailStatus $status): array
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from('emails')
            ->where('status = :status')
            ->setParameter('status', $status->value)
            ->fetchAllAssociative();
    }

    public function markEmailSent(int $id): void
    {
        $this->db->createQueryBuilder()
            ->update('emails')
            ->set('status', ':status')
            ->set('sent_at', ':sent_at')
            ->where('id = :id')
            ->setParameters([
                'status' => EmailStatus::Sent->value,
                'sent_at' => date('Y-m-d H:i:s'),
                'id' => $id
            ])
            ->executeStatement();
    }
}