<?php

namespace App\Services\Brevo;

use Illuminate\Support\Facades\View;

class BrevoMailer
{
    public function __construct(private BrevoClient $client) {}

    public function sendView(
        string $toEmail,
        string $toName,
        string $subject,
        string $view,
        array $data = []
    ): array {
        if (! config('services.brevo.enabled')) {
            return ['disabled' => true];
        }

        $senderName  = (string) config('services.brevo.sender_name');
        $senderEmail = (string) config('services.brevo.sender_email');

        if (! $senderEmail) {
            throw new \RuntimeException('BREVO_SENDER_EMAIL não configurado.');
        }

        $html = View::make($view, $data)->render();

        return $this->client->post('/smtp/email', [
            'sender' => [
                'name'  => $senderName,
                'email' => $senderEmail,
            ],
            'to' => [[
                'email' => $toEmail,
                'name'  => $toName ?: $toEmail,
            ]],
            'subject'     => $subject,
            'htmlContent' => $html,
        ]);
    }
}
