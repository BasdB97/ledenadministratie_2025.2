<?php

class Family
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllFamilies()
  {
    $this->db->query("
            SELECT f.*, 
                   COUNT(fm.id) AS member_count
            FROM family f
            LEFT JOIN family_members fm ON f.id = fm.family_id
            GROUP BY f.id
        ");
    return $this->db->resultSet();
  }

  public function addressExists($data)
  {
    $this->db->query("
            SELECT COUNT(*) as count
            FROM family
            WHERE LOWER(street) = LOWER(:street)
              AND LOWER(house_number) = LOWER(:house_number)
              AND LOWER(postal_code) = LOWER(:postal_code)
              AND LOWER(city) = LOWER(:city)
              AND LOWER(country) = LOWER(:country)
        ");
    $this->db->bind(':street', $data['street']);
    $this->db->bind(':house_number', $data['house_number']);
    $this->db->bind(':postal_code', $data['postal_code']);
    $this->db->bind(':city', $data['city']);
    $this->db->bind(':country', $data['country']);

    $result = $this->db->single();
    return $result->count > 0;
  }

  public function addFamily($data)
  {
    try {
      $this->db->query("
                INSERT INTO family (name, street, house_number, postal_code, city, country)
                VALUES (:name, :street, :house_number, :postal_code, :city, :country)
            ");
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':street', $data['street']);
      $this->db->bind(':house_number', $data['house_number']);
      $this->db->bind(':postal_code', $data['postal_code']);
      $this->db->bind(':city', $data['city']);
      $this->db->bind(':country', $data['country']);

      return $this->db->execute();
    } catch (PDOException $e) {
      error_log('Database error in addFamily: ' . $e->getMessage());
      return false;
    }
  }
}
