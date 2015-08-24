<?php
  require ('DBase.php');

  class Logic {
    private $con;
    private $db;

    public function __construct($_con) {
      $this->con = $_con;
      $this->db = new DBase($this);
    }

    public function endGame($closeConnection = true) {
      // TODO: Close game
      if ($closeConnection)
        $this->con->close();
    }

    public function send($message) {
      $this->con->send($message);
    }

    public function error($message) {
      $this->sendMessage(Messaging::error($message));
      $this->endGame();
    }

  }
