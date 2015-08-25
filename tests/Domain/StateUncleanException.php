<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\EntityInterface;

class StateUncleanException extends \Exception
{

    public function __construct(EntityInterface $entity)
    {
        $fields = implode(", ", $entity->getDirty());
        $message = "Expected entity to be fully serialized, but found unclean state in the following attributes: {$fields}";

        parent::__construct($message);
    }

}
