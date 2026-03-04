<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    protected string $apiUrl;
    protected ?string $apiKey;
    protected ?string $sender;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.url') ?? 'https://waapi.hany-nasr.com/send-message';
        $this->apiKey = config('services.whatsapp.api_key');
        $this->sender = config('services.whatsapp.sender');
    }

    public function send(string $number, string $message, array $options = [])
    {
        $payload = array_merge([
            'api_key' => $this->apiKey,
            'sender' => $this->sender,
            'number' => '2' . $number,
            'message' => $message,
            'footer' => $options['footer'] ?? 'تم الارسال من هاي اكاديمي ستور ',
            'full' => $options['full'] ?? '1',
        ], $options['extra'] ?? []);

        $payload = array_filter($payload, fn($v) => !is_null($v) && $v !== '');

        $response = Http::withoutVerifying()->asForm()->post($this->apiUrl, $payload);


        if ($response->status() == '200') {
            return [
                'status' => true,
                'code' => $response->status(),
                'body' => $response->body(),
            ];
        } else {
            return [
                'status' => false,
                'code' => $response->status(),
                'body' => $response->body(),
            ];
        }


    }
}
