<?php

  class User {
    private $db;
    private $token = "";

    public function __construct($_db, $clientId) {
  		$this->db = $_db;
      $this->token = $this->generateToken($clientId);
  	}

    public function getToken() {
      return $this->token;
    }

    private function generateToken($clientId) {
      if (!empty($token))
        return $token;
      $token = "";
      $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
      $codeAlphabet.= "0123456789";
      //$max = strlen($codeAlphabet) - 1;
      $max = 40;
      for ($i=0; $i < $max; $i++) {
          $token .= $codeAlphabet[$this->crypto_rand_secure(0, strlen($codeAlphabet) - 1)];
      }

      $this->db->prepareInsert([
        'client' => $clientId,
        'token' => $token
      ]);
      $this->db->insert('Session');

      return $token;
    }

    private function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public function handleMessage($logic, $action, $type, $data) {
      echo "Action: $action, Type: $type";

      switch ($action) {
        case 'email_exists':
          if ('request' == $type) {
            if (array_key_exists('email', $data))
              $logic->send(Messaging::response('user', $action, $this->checkUserExists($data['email'])));
            else
              $logic->error("Invalid call to User::emailExists - email not found");
          } else
            $logic->error("Invalid call to User::emailExists");
          break;
        case 'create':
          if ('request' == $type) {
            $params = [];
            foreach (['email', 'password', 'firstname', 'lastname'] as $key => $value) {
              if (!array_key_exists($value, $data))
                $logic->error("Invalid call to User::create - $value not found");
              if (empty($data[$value]))
                $logic->error("Invalid call to User::create - $value is empty");
              $params[$value] = $data[$value];
            }

            //All fields are present and valid
            $exists = $this->checkUserExists($data['email']);
            $exists = $exists['exists'];
            if (!$exists) {
              $this->db->prepareInsert($params);
              $this->db->insert('User');
              //$logic->send(Messaging::response('user', $action, $this->checkUserExists($data['email'])));
            }
          } else
            $logic->error("Invalid call to User::create");
          break;
        default:
          $logic->error("Unknown User action");
          break;
      }
    }

    private function checkUserExists($email) {
      $sql = "SELECT `email` FROM User WHERE `email` = '" . $this->db->escape($email) . "'";
      $users = $this->db->fetchAll($sql);
      return ['exists' => (0 < count($users) && $email == $users[0]['email'])];
    }

    public function sessionActive() {
      $sql = "SELECT `token` FROM Session WHERE active = 1 AND token = '" . $this->db->escape($this->token) . "'";
      $sessions = $this->db->fetchAll($sql);
      return (0 < count($sessions) && $this->token == $sessions[0]['token']);
    }

    public function invalidateSession() {
      $sql = "UPDATE Session SET active = 0 WHERE active = 1 AND token = '" . $this->db->escape($this->token) . "'";
      $this->db->query($sql);
    }

  }
