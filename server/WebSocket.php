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
      if ($this->clients[$con->resourceId])
        $this->clients[$con->resourceId]->endGame(false);
    }

    public function onError(ConnectionInterface $con, \Exception $ex) {
      // TODO: Log error

      try {
        try {
          if ($this->clients[$con->resourceId])
            $this->clients[$con->resourceId]->endGame();
        } catch (Exception $ex) {
          /* Logic might not exist anymore */
        }
        $con->close();
      } catch (Exception $ex) { /* Connection may already be closed */ }
    }

  }


  $webSocketServer = new WsServer(new WebSocket());
  $webSocketServer->disableVersion(0);
  $webSocketServer->setEncodingChecks(0);

  $server = IoServer::factory(new HttpServer($webSocketServer), WebSocket::$port);

  echo "Server starting on port: " . WebSocket::$port . "\n";
  $server->run();
