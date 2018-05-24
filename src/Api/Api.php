<?php


namespace Bixie\Datacollectief\Api;


class Api {

    protected $config = [];

    protected $debug = false;

    /**
     * Api constructor.
     * @param array $config
     * @param bool  $debug
     */
    public function __construct (array $config, $debug) {
        $this->config = $config;
        $this->debug = $debug;
    }


}