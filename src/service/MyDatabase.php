<?php

class MyConnection
{
  private $conn;

  public function __construct(string $host, string $username = "root", string $password, string $database, int $port = 3306)
  {
    $this->conn = new mysqli($host, $username, $password, $database, $port);

    if ($this->conn->connect_errno) {
      die("Failed to connect to database: " . $this->conn->connect_error);
    }
  }

  public function getConnection()
  {
    return $this->conn;
  }

  /**
   * Inserts a new activity record into the database.
   *
   * @param array $data {
   *    @type int       The user ID.
   *    @type string    Type of activity (e.g., create, update, delete, login, logout).
   *    @type string    Additional information about the activity.
   * }
   *
   * @return void
   */
  public function createActivity(array $data)
  {
    // Prepare and bind parameters to prevent SQL injection
    $stmt = $this->conn->prepare("INSERT INTO `reports` (`user_id`, `type_activity`, `info`) VALUES (?, ?, ?)");

    if ($stmt) {
      $stmt->bind_param("iss", $data[0], $data[1], $data[2]);
      $stmt->execute();
      $stmt->close();
    } else {
      die("Error preparing statement: " . $this->conn->error);
    }
  }

  // check $authorization to databases users table password
  public function checkUser($authorization, $type = 'user')
  {
    $sql = "SELECT * FROM users WHERE password = '$authorization'";
    $res = $this->conn->query($sql);

    if ($res->num_rows < 1) {
      return false;
    }

    $role = $res->fetch_assoc()['role'];
    if ($type == $role) {
      return true;
    } else {
      return false;
    }
  }
}
