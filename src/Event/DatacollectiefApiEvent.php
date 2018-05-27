<?php

namespace Bixie\Datacollectief\Event;

use Pagekit\Event\Event;

class DatacollectiefApiEvent extends Event {

    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $processed_data = [];

    /**
     * Constructor.
     * @param string $name
     * @param array $data
     * @param array  $parameters
     */
    public function __construct ($name, $data, array $parameters = []) {
        parent::__construct($name, $parameters);

        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData () {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getProcessedData () {
        return $this->processed_data;
    }

    /**
     * @param array $processed_data
     */
    public function setProcessedData ($processed_data) {
        $this->processed_data = array_merge($this->processed_data, $processed_data);
    }
}
