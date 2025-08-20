<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Mime\Address;

class Email extends Model
{
    protected $table = 'emails';
    
    protected $fillable = [
        'subject',
        'status',
        'text_body',
        'html_body',
        'meta',
        'created_at',
        'sent_at'
    ];
    
    public $timestamps = false; // Since you're manually handling created_at
    public function queue(
        Address $to,
        Address $from,
        string $subject,
        string $html,
        ?string $text = null
    ): int {
        $meta['to'] = $to->toString();
        $meta['from'] = $from->toString();
        
        $email = self::create([
            'subject' => $subject,
            'status' => EmailStatus::Queue->value,
            'text_body' => $text,
            'html_body' => $html,
            'meta' => json_encode($meta),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return $email->id;
    }

    public function getEmailsByStatus(EmailStatus $status): array
    {
        return self::where('status', $status->value)->get()->toArray();
    }

    public function markEmailSent(int $id): void
    {
        self::where('id', $id)->update([
            'status' => EmailStatus::Sent->value,
            'sent_at' => date('Y-m-d H:i:s'),
        ]);
    }
}