<?php

//namespace MyApp;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;


class Socket implements MessageComponentInterface
{
    protected $count = 0;
    protected $connections = array();
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        $this->count++;
        $date = date('h:i A');
        $this->connections[$conn->resourceId] =  $conn->resourceId;
        echo "\e[0;37;42mTotal connections as of \e[0;37;41m {$date}: \e[0m\e[0m\e[0;30;43m " . sizeof($this->connections) . " \e[0m\n";
        //  echo "Total connections as of {$date}: \e[0;32" . sizeof($this->connections) . "\n";
        // echo "{$this->count}) connected::{$conn->resourceId} on {$date}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from->resourceId == $client->resourceId) {
                continue;
            }
            //$client->send("Client $from->resourceId said $msg");
            $client->send($msg);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        unset($this->connections[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
    }
}
