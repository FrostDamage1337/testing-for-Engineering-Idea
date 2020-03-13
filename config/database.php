<?php

class Database {
  
  private $host = 'localhost';
  private $port = '5432';
  private $user = 'postgres';
  private $password = '13371488';
  public $conn;

  public function establishConnection() {
    $this->conn = null;
   
    try {
      $this->conn = pg_connect("host=".$this->host." port=".$this->port." dbname=postgres user=".$this->user." password=".$this->password);
    } catch (PDOException $exception) {
      echo 'Something wrong with database connection - ' . $exception->getMessage();
    }
    return $this->conn;
  }
}
