<?php
namespace SMSTransmitter\MessageBird;

// MessageBird
use MessageBird\Common\HttpClient;
use MessageBird\Client as MessagebirdClient;

// Config
use SMSTransmitter\Config\Config;

/**
 * Class MessagebirdTransmitter
 * @package SMSTransmitter\MessageBird 
 */
class MessagebirdTransmitter
{

    /**
     * 
     * @param  MessageBird\Objects\Message $messagebirdMessage 
     * @param  array $parameters         
     * @param  SMSTransmitter\TransportControl\PartialMessage $partialMessage     
     * @return Messagebird\Resources\Base Result from the MessageBird plateform
     */
    public function sendPartialMessage($messagebirdMessage, $parameters, $partialMessage)
    {
        // Extend both CURL timeout & CURL connectionTimeout to 60 seconds.
        $httpClient = new HttpClient(MessagebirdClient::ENDPOINT, 60000, 60);
        $messageBirdClient = new MessagebirdClient(Config::MESSAGEBIRD_API_KEY, $httpClient);

        $messagebirdMessage->typeDetails["udh"]=(string)$partialMessage->getUdh();
        $messagebirdMessage->originator=$parameters["originator"];
        $messagebirdMessage->recipients = [($parameters["recipient"])];
        $messagebirdMessage->body=$partialMessage->getContent();
        
        $messageResult = $messageBirdClient->messages->create($messagebirdMessage);
        return $messageResult;
    }

    /**
     * 
     * @param  MessageBird\Objects\Message $messagebirdMessage 
     * @param  array $parameters         
     * @return Messagebird\Resources\Base Result from the MessageBird plateform
     */
    public function sendMessage($messagebirdMessage, $parameters)
    {
        // Extend CURL timeout & CURL connectionTimeout to 60 seconds both.
        $httpClient=new HttpClient(MessagebirdClient::ENDPOINT, 60000, 60);
        $messageBirdClient = new MessagebirdClient(Config::MESSAGEBIRD_API_KEY, $httpClient);

        $messagebirdMessage->originator=$parameters["originator"];
        $messagebirdMessage->recipients = [($parameters["recipient"])];
        $messagebirdMessage->body=$parameters['body'];
        $messageResult = $messageBirdClient->messages->create($messagebirdMessage);
        return $messageResult;
    }
}
