<?php
  require ('DBase.php');
  require ('Messaging.php');
  require ('User.php');

  class Logic {
    private $con;
    private $db;
    private $user;
    private $quiet = false;

    public function __construct($_con) {
      $this->con = $_con;
      $this->db = new DBase($this);
      $this->user = new User($this->db, $this->con->resourceId);
      $this->send(Messaging::request('user', 'login', ['token' => $this->user->getToken()]));
    }

    public function receiveMessage($message) {
      if (!$this->quiet)
        echo "Receive (" . $this->con->resourceId . ") - $message\n";

      if (!$this->user->sessionActive())
        $this->error(Messaging::error("You have been logged out"));

      try {
        $message = json_decode($message, !0);
      } catch (Exception $ex) {
        $this->error(Messaging::error("Error converting JSON message to object"));
      }

      if (!array_key_exists('event', $message))
        $this->error(Messaging::error("Event was not passed in message"));
      if (!array_key_exists('action', $message))
        $this->error(Messaging::error("Action was not passed in message"));

      $data = (array_key_exists('data', $message) ? $message['data'] : []);
      $message['event'] = strtolower($message['event']);
      $message['action'] = strtolower($message['action']);

      $matches = [];
      if (!preg_match('/^(.+)_(request|response)$/', $message['action'], $matches) && 3 == count($matches))
        $logic->error("Invalid call to User function - message type not found");

      $message['action'] = $matches[1];
      $message['type'] = $matches[2];

      try{
        switch ($message['event']) {
          case 'user':
            $this->user->handleMessage($this, $message['action'], $message['type'], $data);
            break;
          default:
            $this->error(Messaging::error("Unknown event"));
            break;
        }
      } catch (Exception $ex) {
        // End game rather than kill server
        $this->endGame();
      }
    }

    public function endGame($closeConnection = true) {
      if (!$this->quiet)
        echo "Game Closing - Closing Connection: " . ($closeConnection ? "true" : "false") . "\n";

      try {
        $this->user->invalidateSession();
      } catch (Exception $ex) {
        /* User could be destroyed already */
      }

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
