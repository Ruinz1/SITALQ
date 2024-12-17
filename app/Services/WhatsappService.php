<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private const API_URL = 'https://api.alatwa.com/send/text';
    private const DEVICE_ID = '888125768638';
    private const API_KEY = 'bdfc90042d21b575bfa2c32e8a20a44f';

    public function sendMessage(string $phoneNumber, string $message): bool
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            Log::info('Mencoba mengirim pesan WhatsApp', [
                'to' => $formattedPhone,
                'device_id' => self::DEVICE_ID,
                'message_length' => strlen($message)
            ]);

            $response = $this->makeApiRequest($formattedPhone, $message);
            
            $responseData = json_decode($response, true);
            
            Log::info('Response WhatsApp API', [
                'response' => $responseData,
                'status' => $responseData['status'] ?? 'unknown',
                'message' => $responseData['message'] ?? 'no message',
                'raw_response' => $response
            ]);

            return isset($responseData['status']) && $responseData['status'] === 'success';

        } catch (\Exception $e) {
            Log::error('Error pada WhatsApp Service', [
                'error' => $e->getMessage(),
                'phone' => $phoneNumber
            ]);
            return false;
        }
    }

    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        $formatted = preg_replace('/^0/', '62', $phone);
        
        Log::info('Format nomor telepon', [
            'original' => $phone,
            'formatted' => $formatted
        ]);

        return $formatted;
    }

    private function makeApiRequest(string $phone, string $message): string
    {
        $postData = [
            "device" => self::DEVICE_ID,
            "phone" => $phone,
            "message" => $message
        ];

        Log::info('Mengirim request ke API WhatsApp', [
            'url' => self::API_URL,
            'phone' => $phone,
            'device_id' => self::DEVICE_ID
        ]);

        $curl = curl_init(self::API_URL);
        
        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: " . self::API_KEY
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($curl);
        
        Log::debug('Raw API Response', [
            'response' => $response,
            'curl_info' => curl_getinfo($curl)
        ]);

        if (curl_errno($curl)) {
            Log::error('CURL Error', [
                'error' => curl_error($curl),
                'errno' => curl_errno($curl),
                'http_code' => curl_getinfo($curl, CURLINFO_HTTP_CODE)
            ]);
        }

        curl_close($curl);

        return $response;
    }
}