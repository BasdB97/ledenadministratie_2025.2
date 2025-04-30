<?php

class Bookyear
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllBookyears()
  {
    $this->db->query("SELECT * FROM bookyear ORDER BY year DESC");
    return $this->db->resultSet();
  }
}
