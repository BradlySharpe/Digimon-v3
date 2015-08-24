<?php
  require ('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
  require ('Logic.php');
  use Ratchet\Server\IoServer;
  use Ratchet\Http\HttpServer;
  use Ratchet\WebSocket\WsServer;
  use Ratchet\MessageComponentInterface;
  use Ratchet\ConnectionInterface;

  class WebSocket implements MessageComponentInterface{
    protected $clients = [];
    public static $port = 8080;

    public function __construct() {}

    public function onOpen(ConnectionInterface $con) {
      $this->clients[$con->resourceId] = new Logic($con);
    }

    public function onMessage(ConnectionInterface $con, $message) {
      if ($this->clients[$con->resourceId])
        $this->clients[$con->resourceId]->receiveMessage($message);
      else
        $con->close();
    }

    public function onClose(ConnectionInterface $con) {
      // The connection is closed, remove it, as we can no longer send it messages
      //echo "Connection {$con->resourceId} has disconnected\n";
      if ($this->clients[$con->resourceId])
        $this->clients[$con->resourceId]->logout();
    }

    public function onError(ConnectionInterface $con, \Exception $ex) {
      //echo "An error has occurred: {$ex->getMessage()}\n";
      // TODO: Log error
      $con->close();
    }

  }


  $webSocketServer = new WsServer(new WebSocket());
  $webSocketServer->disableVersion(0);
  $webSocketServer->setEncodingChecks(!1);

  $server = IoServer::factory(new HttpServer($webSocketServer), WebSocket::$port);

  echo "Server starting on port: " . WebSocket::$port . "\n";
  $server->run();
