<?php
namespace vendor\seek;

abstract class EventGenerator
{
    private $obServers = array();
    public function addObserver(ObServer $obServer)
    {
        $this->obServers[] = $obServer;
    }

    public function notify()
    {
        foreach ($this->obServers as $server) {
            $server->update();
        }
    }
}