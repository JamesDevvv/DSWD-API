<?php
namespace App\Services;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use GuzzleHttp\Client;

class OtpService
{
    protected $apiInstance;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('sendinblue.api_key'));
        $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);
    }

    public function sendOtp($email, $otp)
    {
        $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
            'to' => [[
                'email' => $email,
            ]],
            'sender' => [
                'name' => 'DSWD PROJECT RESOLVE',
                'email' => 'noreply082524@gmail.com',
            ],
            'subject' => 'Your OTP Code',
            'htmlContent' => view('emails.Otp', ['otp' => $otp])->render(),
        ]);

        try {
            $this->apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            throw new \Exception('Unable to send OTP email: ' . $e->getMessage());
        }
    }
}
