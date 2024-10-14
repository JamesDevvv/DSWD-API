<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalSMSApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendTransacSms;
use GuzzleHttp\Client;

class SmsService
{
    protected $apiInstance;

    public function __construct()
    {
        // Initialize configuration with your Sendinblue API key.
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('sendinblue.api_key'));
        $this->apiInstance = new TransactionalSMSApi(new Client(), $config);
    }

    public function sendSms($to, $content, $tag = null)
    {
        try {
            // Create a SendTransacSms object with necessary parameters.
            $sendTransacSms = new SendTransacSms([
                'recipient' => $to,               // Phone number of the recipient
                'sender' => 'DSWDPROJECT',        // Sender ID (must be compliant with regulations)
                'content' => $content,            // SMS message content
                'type' => 'transactional',        // Message type: transactional or marketing
                'tag' => $tag                     // Optional tag for the SMS
                // 'callback' => 'http://callbackurl.com/' // Callback URL, if needed
            ]);

            // Send the SMS and capture the response
            $result = $this->apiInstance->sendTransacSms($sendTransacSms);

            // Return the result of the send operation.
            return $result;

        } catch (\Exception $e) {
            \Log::error('SMS sending failed: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}
