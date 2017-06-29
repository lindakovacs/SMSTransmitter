<?php
use SMSTransmitter\Controller\FrontController;

require_once 'app/start.php';

$frontController = new FrontController();

$frontController->indexAction();
