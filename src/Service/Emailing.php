<?php

namespace App\Service;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

class Emailing
{
    public function sendEmailing(array $emailUser, int $idTemplate, $token, $club)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['MAILER_API_KEY']);
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        if ($club) {
            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
            $sendSmtpEmail['to'] = array(array('email' => $emailUser[0], 'name' => 'John Doe'));
            $sendSmtpEmail['templateId'] = $idTemplate;
            $sendSmtpEmail['params'] = array('url' => $token, 'email' => $emailUser[0], 'club' => $club->getId());
        } else {
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
        $sendSmtpEmail['to'] = array(array('email' => $emailUser[0], 'name' => 'John Doe'));
        $sendSmtpEmail['templateId'] = $idTemplate;
        $sendSmtpEmail['params'] = array('url' => $token, 'email' => $emailUser[0]);
        }
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }

        return true;
    }
}