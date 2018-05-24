<?php

namespace Bixie\Datacollectief\Event;

use Pagekit\Event\Event;

class DatacollectiefApiEvent extends Event {

    /**
     * @var array
     */
    protected $data;

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

}
