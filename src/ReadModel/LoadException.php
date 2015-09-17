<?php

namespace Depotwarehouse\Blumba\ReadModel;

class LoadException extends \Exception
{

    /**
     * @param string $type
     * @param string $fieldNotFound
     */
    public function __construct($type, $fieldNotFound)
    {
        $message = "Could not eager load {$type}, {$fieldNotFound} was not found in eager load data";
        parent::__construct($message);
    }

}
