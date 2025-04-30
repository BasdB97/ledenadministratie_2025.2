<?php

class FamilyMember
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllFamilyMembers()
  {
    $this->db->query("SELECT * FROM family_member");
    return $this->db->resultSet();
  }

  public function calculateAge($dateOfBirth)
  {
    $birthDate = new DateTime($dateOfBirth);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
  }

  public function updateMemberType($memberId, $memberType)
  {
    $this->db->query("UPDATE family_member SET member_type = :member_type WHERE id = :id");
    $this->db->bind(':member_type', $memberType);
    $this->db->bind(':id', $memberId);
    return $this->db->execute();
  }
} 