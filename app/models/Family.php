<?php

class Family
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getFamiliyByMemberId($memberId)
  {
    $this->db->query("SELECT f.* FROM family f
                      INNER JOIN family_members fm ON f.id = fm.family_id
                      WHERE fm.id = :member_id");
    $this->db->bind(':member_id', $memberId);
    return $this->db->single();
  }
}
