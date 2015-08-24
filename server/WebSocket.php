<?php
  require ('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
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
      // Store the new connection to send messages to later
      //$this->clients->attach($con);
      //echo "New connection! ({$con->resourceId})\n";
      $this->clients[$con->resourceId] = array(
        'con' => $con
      );
    }

    public function onMessage(ConnectionInterface $from, $message) {
      //echo sprintf('Received message from: %d - %s' . "\n", $from->resourceId, $message);
    }

    public function onClose(ConnectionInterface $con) {
      // The connection is closed, remove it, as we can no longer send it messages
      //echo "Connection {$con->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $con, \Exception $ex) {
      //echo "An error has occurred: {$ex->getMessage()}\n";
      $con->close();
    }

    private function _getGame($con) {
      return $this->clients[$con->resourceId]['game'];
    }
  }

  $webSocketServer = new WsServer(
    new WebSocket()
  );
  $webSocketServer->disableVersion(0);
  $webSocketServer->setEncodingChecks(!1);

  $server = IoServer::factory(
    new HttpServer($webSocketServer),
    WebSocket::$port
  );

  echo "Server starting on port: " . WebSocket::$port . "\n";
  $server->run();
