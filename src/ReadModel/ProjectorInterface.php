<?php


namespace Depotwarehouse\Blumba\ReadModel;


use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;
use Illuminate\Database\ConnectionInterface;

interface ProjectorInterface
{

    public function project(SerializableEventInterface $event);

    public static function initialize(ConnectionInterface $connection);
}
