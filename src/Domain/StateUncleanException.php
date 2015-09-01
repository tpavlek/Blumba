<?php

namespace Depotwarehouse\Blumba\Domain;

use Depotwarehouse\Blumba\Domain\EntityInterface;
use Exception;

class StateUncleanException extends \Exception
{

    public function __construct(EntityInterface $entity)
    {
        $fields = implode(", ", $entity->getDirty());
        $shortEntityName = (new \ReflectionClass($entity))->getShortName();
        $message = "Expected {$shortEntityName} to be fully serialized, but found unclean state in the following attributes: {$fields}";

        parent::__construct($message);
    }

}
