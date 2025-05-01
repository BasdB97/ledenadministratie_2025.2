<?php

class Family
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }
}
