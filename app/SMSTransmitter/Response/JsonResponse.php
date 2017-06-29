<?php
namespace SMSTransmitter\Response;

use SMSTransmitter\Config\Config;

/**
 * Class JsonResponse
 * @package SMSTransmitter\Response 
 */
class JsonResponse
{

    
    // STATUS CODE COSNTANTS
    
    const STATUS_OK = 200;

    const STATUS_BAD_REQUEST = 400;

    const STATUS_UNAUTHORIZED = 401;

    const STATUS_METHOD_NOT_ALLOWED = 405;

    const STATUS_INTERNAL_SERVER_ERROR=500;


    // HEADER COSNTANTS
    
    const HEADER_JSON_UTF8="Content-Type: application/json; charset: UTF-8";

    /**
     * 
     * @var int
     */
    private $status;

    /**
     * 
     * @var string
     */
    private $content;

    /**
     * 
     * @var string
     */
    private $headers;

    /**
     * 
     * @param string $content 
     * @param int $status
     * @param string $headers
     */
    public function __construct($content=null, $status=null, $headers=null)
    {
        $this->status=($status==null? self::STATUS_OK:$status);
        
        $this->headers=[self::HEADER_JSON_UTF8];
        $this->headers=($headers==null?$this->headers:$headers);
        
        $this->content=($content==null? "{}":json_encode($content));
    }

    // Begin Getters & Setters
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status=$status;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content=json_encode($content);
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers)
    {
        $this->headers=$headers;
        return $this;
    }
    // End of Getters & Setters
}
