<?php
namespace SMSTransmitter\TransportControl;

use SMSTransmitter\TransportControl\Udh;
use SMSTransmitter\TransportControl\LengthControl;
use SMSTransmitter\Exceptions\PartialMessageException;

/**
 * Class PartialMessage
 * @package SMSTransmitter\TransportControl
 */
class PartialMessage
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var SMSTransmitter\TransportControl\Udh
     */
    private $udh;

    /**
     * 
     * @param string   $content
     * @param SMSTransmitter\TransportControl\Udh|null $udh
     */
    public function __construct($content=null, Udh $udh=null)
    {
        if ($content!=null) {
            $this->setContent($content);
        }
        if ($udh!=null) {
            $this->setUdh($udh);
        }
    }
    

    // Getters & Setters
    public function getUdh()
    {
        return $this->udh;
    }

    public function setUdh(Udh $udh)
    {
        $this->udh=$udh;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content=$content;
        return $this;
    }

    // End of // Getters & Setters
}
