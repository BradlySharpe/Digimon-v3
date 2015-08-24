<?php
  require ('DBase.php');
  require ('Messaging.php');
  require ('User.php');

  class Logic {
    private $con;
    private $db;
    private $quiet = false;

    public function __construct($_con) {
      $this->con = $_con;
      $this->db = new DBase($this);
    }

    public function receiveMessage($message) {
      if (!$this->quiet)
        echo "Receive (" . $this->con->resourceId . ") - $message\n";

      // TODO: Handle message
    }

    public function endGame($closeConnection = true) {
      if (!$this->quiet)
        echo "Game Closing\n";

      // TODO: Close game

      if ($closeConnection) {
        try {
          $this->con->close();
        } catch (Exception $ex) { /* Game may have already closed */ }
      }
    }

    public function send($message) {
      if (!$this->quiet)
        echo "Send (" . $this->con->resourceId . ") - $message\n";
      $this->con->send($message);
    }

    public function error($message) {
      $this->send(Messaging::error($message));
      $this->endGame();
    }

  }
