<?php
  require ('DBase.php');

  class Logic {
    private $con;
    private $db;

    public function __construct($_con) {
      $this->con = $_con;
      $this->db = new DBase($this);
    }

  }
