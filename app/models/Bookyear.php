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

  public function getBookyearByYear($year)
  {
    $this->db->query("SELECT * FROM bookyear WHERE year = :year");
    $this->db->bind(':year', $year);
    return $this->db->single();
  }

  public function getActiveBookyear()
  {
    $this->db->query("SELECT * FROM bookyear WHERE is_active = 1");
    return $this->db->single();
  }
}
