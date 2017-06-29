<?php
namespace SMSTransmitter\TransportControl;

use SMSTransmitter\Config\Config as Config;

/**
 * Class RateControl
 * @package SMSTransmitter\TransportControl
 * Credit: Solution inspired by this stack overflow answer: https://stackoverflow.com/questions/4257678/php-rate-limiting-client
 */
class RateControl
{

    /**
     * Number of allowed operations ** PER SECOND **
     * @var double
     */
    private $rate;

    /**
     * Internally used to check the last time we made check on the passed time
     * @var double
     */
    private $last_check;

    /**
     * @var double
     */
    private $allowance;

    /**
     * Property initializations
     * @param double $rate 
     */
    public function __construct($rate=null)
    {
        if ($rate==null) {
            $rate=Config::MAX_REQUEST_RATE;
        }
        $this->rate=$rate;
        $this->last_check=0;
        $this->allowance=$this->rate;
    }

    
    /**
     * 
     * @param  int $consumed 
     * @return void
     */
    public function limit($consumed = 1)
    {
        $currentTime = time(true);
        $time_passed = $currentTime - $this->last_check;
        $this->last_check = $currentTime;

        $this->allowance += $time_passed * ($this->rate);

        if ($this->allowance > $this->rate) {
            $this->allowance = $this->rate;
        }

        if ($this->allowance < $consumed) {
            $duration = ($consumed - $this->allowance) * (1 / $this->rate);
            $this->last_check += $duration;
            usleep($duration*1000000);
            $this->allowance = 0;
        } else {
            $this->allowance -= $consumed;
        }
    }
}
