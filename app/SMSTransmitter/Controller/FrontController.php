<?php
namespace SMSTransmitter\Controller;

// MessagebirdTransmitter
use SMSTransmitter\MessageBird\MessagebirdTransmitter;


// MessagebirdMessage
use MessageBird\Objects\Message as MessagebirdMessage;


// Validator
use SMSTransmitter\Validator\RequestValidator;

// JsonResponse
use SMSTransmitter\Response\JsonResponse;

// SMSTransmitter Exceptions
use SMSTransmitter\Exceptions\MissingParametersException;
use SMSTransmitter\Exceptions\UnknownParametersException;
use SMSTransmitter\Exceptions\EmptyParametersException;
use SMSTransmitter\Exceptions\LengthControlException;

// MessageBird Exceptions
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\BalanceException;

// Transport Control
use SMSTransmitter\TransportControl\RateControl;
use SMSTransmitter\TransportControl\LengthControl;

/**
 * Class FrontController
 * @package SMSTransmitter\Controller 
 */
class FrontController
{
    public function indexAction()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $parameters = $this->getParameters($method);
        $this->validateRequest($method, $parameters);

        $messagebirdMessage = new MessagebirdMessage();
        $messagebirdTransmitter = new MessagebirdTransmitter();
        $lengthControl = new LengthControl();

        if ($lengthControl->isTooLong($parameters["body"])) {
            try {
                $splitMessages = $lengthControl->split($parameters["body"]);
            } catch (LengthControlException $e) {
                echo $e->getMessage();
                exit();
            }
            $rateControl = new RateControl();
            $messageResult=[];
            foreach ($splitMessages as $partialMessage) {
                try {
                    $partialMessageResult=$messagebirdTransmitter->sendPartialMessage($messagebirdMessage, $parameters, $partialMessage);
                } catch (AuthenticateException $e) {
                    $errorResponse = new JsonResponse(["error"=>"wrong login"], JsonResponse::STATUS_UNAUTHORIZED);
                    $this->returnResponse($errorResponse);
                } catch (BalanceException $e) {
                    $errorResponse = new JsonResponse(["error"=>"no balance"], JsonResponse::STATUS_BAD_REQUEST);
                    $this->returnResponse($errorResponse);
                } catch (\Exception $e) {
                    $errorResponse = new JsonResponse(["error"=>$e->getMessage()], JsonResponse::STATUS_INTERNAL_SERVER_ERROR);
                    $this->returnResponse($errorResponse);
                }
                $messageResult[]=$partialMessageResult;
            }
            foreach ($messageResult as $partialMessageResult) {
                $rateControl->limit();
                $successResponse =  new JsonResponse(["success"=>$partialMessageResult], JsonResponse::STATUS_OK);
                $this->returnResponse($successResponse);
            }
        } else {
            try {
                $messageResult=$messagebirdTransmitter->sendMessage($messagebirdMessage, $parameters);
            } catch (AuthenticateException $e) {
                $errorResponse = new JsonResponse(["error"=>"wrong login"], JsonResponse::STATUS_UNAUTHORIZED);
                $this->returnResponse($errorResponse);
            } catch (BalanceException $e) {
                $errorResponse = new JsonResponse(["error"=>"no balance"], JsonResponse::STATUS_BAD_REQUEST);
                $this->returnResponse($errorResponse);
            } catch (\Exception $e) {
                $errorResponse = new JsonResponse(["error"=>$e->getMessage()], JsonResponse::STATUS_INTERNAL_SERVER_ERROR);
                $this->returnResponse($errorResponse);
            }

            $successResponse =  new JsonResponse(["success"=>$messageResult], JsonResponse::STATUS_OK);
            $this->returnResponse($successResponse);
        }
    }

    /**
     * 
     * @return array the current method array($_GET,$_POST, $_PUT, $_DELETE)
     */
    private function getParameters()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case "GET":
                return $_GET;
            case "POST":
                return $_POST;
            case "PUT":
                return $_PUT;
            case "DELETE":
                return $_DELETE;
        }
    }

    /**
     * 
     * @param  string $method    
     * @param  array $parameters 
     * @return void
     * @throws RequestMethodException
     * @throws MissingParametersException
     * @throws UnknownParametersException
     * @throws EmptyParametersException
     * @throws \Exception
     * 
     */
    private function validateRequest($method, $parameters)
    {
        $requestValidator = new RequestValidator();
        try {
            $requestValidator->validate($method, $parameters);
        } catch (RequestMethodException $e) {
            $errorResponse = new JsonResponse(["error"=>$e->getMessage()], JsonResponse::STATUS_METHOD_NOT_ALLOWED);
            $this->returnResponse($errorResponse);
        } catch (MissingParametersException $e) {
            $errorResponse = new JsonResponse(["error"=>$e->getMessage()], JsonResponse::STATUS_BAD_REQUEST);
            $this->returnResponse($errorResponse);
        } catch (UnknownParametersException $e) {
            $errorResponse = new JsonResponse(["error"=>$e->getMessage()], JsonResponse::STATUS_BAD_REQUEST);
            $this->returnResponse($errorResponse);
        } catch (EmptyParametersException $e) {
            $errorResponse = new JsonResponse(["error"=>$e->getMessage()], JsonResponse::STATUS_BAD_REQUEST);
            $this->returnResponse($errorResponse);
        } catch (\Exception $e) {
            $errorResponse = new JsonResponse(["error"=>$e->getMessage()], JsonResponse::STATUS_INTERNAL_SERVER_ERROR);
            $this->returnResponse($errorResponse);
        }
    }

    /**
     * 
     * @param  string $response 
     * @return  void
     */
    private function returnResponse($response)
    {
        if (!headers_sent()) {
            foreach ($response->getHeaders() as $header) {
                header($header);
            }
        }
        http_response_code($response->getStatus());
        echo($response->getContent());
        exit();
    }
}
