<?php

namespace App\Service;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

class Emailing
{
    public function sendEmailing(array $emailUser, int $idTemplate, $token)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['MAILER_DSN']);

        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
        $sendSmtpEmail['to'] = array(array('email'=>$emailUser[0], 'name'=>'John Doe'));
        $sendSmtpEmail['templateId'] = $idTemplate;
        $sendSmtpEmail['params'] = array('url'=>$token, 'email'=>$emailUser[0]);
        //$sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'custom_header_1:custom_value_1|custom_header_2:custom_value_2');


        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }

        return true;
    }
}