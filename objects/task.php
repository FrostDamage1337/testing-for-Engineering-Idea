<?php

class Task {
  private $conn;
  
  public $task_id;
  public $title;
  public $description;
  public $status;
  public $user_id;
 
  public function __construct($db_conn)
  {
    $this->conn = $db_conn;
  }

  public function createTask($title, $description, $user_id)
  {
    $result = pg_query($this->conn, "INSERT INTO tasks (title, description, user_id) VALUES ('" . $title . "', '" . $description . "', '" . $user_id . "')");
    if (!$result)
      return json_encode([ "result" => 0, "status" => "Error" ]);
    return json_encode([ "result" => 1, "status" => "OK" ]);
  }

  public function editTask($task_id, $title = '', $description = '')
  {
    if ($title != '' || $description != '')
    {
      $conn_str = "UPDATE tasks SET" . ($title == '' ? '' : " title = '" . $title . "',") . ($description == '' ? '' : " description = '" . $description . "',");
      $conn_str = substr($conn_str, 0, -1); 
      $conn_str .= " WHERE task_id = " . $task_id;
      $result = pg_query($this->conn, $conn_str);
      return json_encode([ "result" => 1, "status" => "OK" ]);
    } else {
      return json_encode([ "result" => 1, "status" => "Past at least one field" ]);
    }
  }

  public function editTaskStatus($task_id, $status)
  {
    if (!in_array($status, [ "View", "In Progress", "Done" ]))
    {
      return json_encode([ "result" => 0, "status" => "Choose appropriate status" ]);
    }
    pg_query($this->conn, "UPDATE tasks SET status = '" . $status . "' WHERE task_id = " . $task_id);
    return json_encode([ "result" => 1, "status" => "OK" ]);
  }

  public function deleteTask($task_id)
  {
    pg_query($this->conn, "DELETE FROM tasks WHERE task_id = " . $task_id);
    return json_encode([ "result" => 0, "status" => "OK" ]);
  }

  public function getTasks($status_filter = '', $sorting_id = false)
  {
    $result = pg_query($this->conn, "SELECT * FROM tasks" . ($status_filter == '' ? '' : " WHERE status LIKE '%" . $status_filter . "%'") . (!$sorting_id ? '' : " ORDER BY task_id"));
    if (!$result)
      return json_encode([ "result" => 0, "status" => "Error" ]);
    $json_array = [ "status" => "OK" ];
    while ($row = pg_fetch_object($result))
    {
      $json_array["result"][$row->task_id] = [ "title" => $row->title, "description" => $row->description, "status" => $row->status, "user_id" => $row->user_id ];
    }
    return json_encode($json_array);
  }

  public function changeTaskUser($task_id, $user_id)
  {
    $result = pg_query($this->conn, "UPDATE tasks SET user_id = " . $user_id . " WHERE task_id = " . $task_id);
    if (!$result)
      return json_encode([ "result" => 0, "status" => "Error" ]);
    return json_encode([ "result" => 1, "status" => "OK" ]);
  }
}
