<?php

class User {
  private $conn;
  
  public $user_id;
  public $first_name;
  public $last_name;

  public function __construct($db_conn)
  {
    $this->conn = $db_conn;
  }
  
  public function createUser($first_name, $last_name)
  {
    $result = pg_query($this->conn, "INSERT INTO users (first_name, last_name) VALUES ('" . $first_name . "', '" . $last_name . "')");
    if (!$result)
      return json_encode([ "result" => 0, "status" => "Error" ]);
    return json_encode([ "result" => 1, "status" => "OK" ]);
  }
  
  public function editUser($user_id, $first_name = '', $last_name = '')
  {
    if ($first_name != '' || $last_name != '')
    {
      $conn_str = "UPDATE users SET" . ($first_name == '' ? '' : " first_name = '" . $first_name . "',") . ($last_name == '' ? '' : " last_name = '" . $last_name . "',"); 
      $conn_str = substr($conn_str, 0, -1); 
      $conn_str .= " WHERE user_id = " . $user_id;
      $result = pg_query($this->conn, $conn_str);
      return json_encode([ "result" => 1, "status" => "OK" ]);
    } else {
      return json_encode([ "result" => 0, "status" => "Past at least one field" ]);
    }
  }

  public function deleteUser($user_id)
  {
    $result = pg_query($this->conn, "DELETE FROM users WHERE user_id = " . $user_id);
    if (!$result)
      return json_encode([ "result" => 0, "status" => "Error" ]);
    return json_encode([ "result" => 1, "status" => "OK" ]);
  }

  public function getUsers()
  {
    $result = pg_query($this->conn, "SELECT * FROM users");
    $json_array = [ "status" => "OK" ];
    while ($row = pg_fetch_object($result))
    {
      $json_array["result"][$row->user_id] = [ "first_name" => $row->first_name, "last_name" => $row->last_name ];
    }
    if (count($json_array) == 0)
      return json_encode([ "result" => 0, "status" => "There is no users" ]);
    return json_encode($json_array);
  }
}
