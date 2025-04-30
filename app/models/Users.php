<?php

class Users
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function findUserByUsername($username)
  {
    $this->db->query('SELECT * FROM users WHERE username = :username');
    $this->db->bind(':username', $username);
    $row = $this->db->single();
    return $this->db->rowCount() > 0;
  }

  public function login($username, $password)
  {
    $this->db->query('SELECT * FROM users WHERE username = :username');
    $this->db->bind(':username', $username);
    $row = $this->db->single();
    return password_verify($password, $row->password) ? $row : false;
  }
}
