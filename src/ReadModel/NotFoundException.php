<?php

namespace Depotwarehouse\Blumba\ReadModel;

class NotFoundException extends \Exception
{

    public function __construct($search_type, $search_criteria)
    {
        $message = "Could not find any {$search_type} matching criteria: {$search_criteria}";
        parent::__construct($message);
    }

}
