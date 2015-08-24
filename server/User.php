<?php

  class User {
    private $db;
    private $token;

    public function __construct($_db) {
  		$this->db = $_db;
      $this->token = time();
  	}

    public function generateToken() {
      return $this->token;
    }

    public function handleMessage($logic, $action, $data) {
      
    }

  }
