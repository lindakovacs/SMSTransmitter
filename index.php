<?php
use SMSTransmitter\Controller\FrontController;

require_once 'app/start.php';


        /*$Message             = new \MessageBird\Objects\Message();
        $Message->originator = 'MessageBird';
        $Message->recipients = array("+21696390001");
        $Message->body       = 'This is a test message.';
        $Message->setBinarySms(0x050003CC0101, "123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 ");
        $messageBirdClient = new \MessageBird\Client("aBwzuetKTJiaXmYc0CKqEepUQ");
        $messageResult = $messageBirdClient->messages->create($Message);
        echo json_encode($messageResult);*/
$frontController = new FrontController();

$frontController->indexAction();
