<?php


namespace Bixie\Datacollectief\Api;


use Pagekit\Application\Exception;
use Throwable;

class DatacollectiefApiException extends Exception {
    /**
     * Status from API https://api.datacollectief.nl/Foutmeldingen
     * @var string
     */
    protected $statusText;

    /**
     * DatacollectiefApiException constructor.
     * @param string         $statusText
     * @param int            $code
     * @param string         $message
     * @param Throwable|null $previous
     */
    public function __construct ($statusText = '', $code = 0, $message = '', Throwable $previous = null) {
        $this->statusText = $statusText;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getStatusText () {
        return $this->statusText;
    }

    /**
     * @param mixed $statusText
     * @return DatacollectiefApiException
     */
    public function setStatusText ($statusText) {
        $this->statusText = $statusText;
        return $this;
    }


}