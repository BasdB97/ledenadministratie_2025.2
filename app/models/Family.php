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


  public function getFamilyById($id)
  {
    $this->db->query("SELECT * FROM family WHERE id = :id");
    $this->db->bind(':id', $id);
    return $this->db->single();
  }

  public function getFamilyMembers($id)
  {
    $this->db->query("SELECT * FROM family_members WHERE family_id = :id");
    $this->db->bind(':id', $id);
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

  public function addressExistsForOtherFamily($data, $currentFamilyId)
  {
    $this->db->query("
        SELECT COUNT(*) as count
        FROM family
        WHERE LOWER(street) = LOWER(:street)
          AND LOWER(house_number) = LOWER(:house_number)
          AND LOWER(postal_code) = LOWER(:postal_code)
          AND LOWER(city) = LOWER(:city)
          AND LOWER(country) = LOWER(:country)
          AND id != :exclude_id
    ");
    $this->db->bind(':street', $data['street']);
    $this->db->bind(':house_number', $data['house_number']);
    $this->db->bind(':postal_code', $data['postal_code']);
    $this->db->bind(':city', $data['city']);
    $this->db->bind(':country', $data['country']);
    $this->db->bind(':exclude_id', $currentFamilyId);

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

  public function updateFamily($data)
  {
    try {
      $this->db->query("
                UPDATE family
                SET name = :name, street = :street, house_number = :house_number, postal_code = :postal_code, city = :city, country = :country
                WHERE id = :id
            ");
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':street', $data['street']);
      $this->db->bind(':house_number', $data['house_number']);
      $this->db->bind(':postal_code', $data['postal_code']);
      $this->db->bind(':city', $data['city']);
      $this->db->bind(':country', $data['country']);

      return $this->db->execute();
    } catch (PDOException $e) {
      error_log('Database error in updateFamily: ' . $e->getMessage());
      return false;
    }
  }

  public function deleteFamily($familyId, $bookyear_id)
  {
    try {
      $this->db->beginTransaction();

      // Haal alle member_ids op voor de familie
      $this->db->query("
                SELECT id 
                FROM family_members 
                WHERE family_id = :family_id
            ");
      $this->db->bind(':family_id', $familyId);
      $members = $this->db->resultSet();
      $memberIds = array_column($members, 'id');

      if (!empty($memberIds)) {
        // Verwijder betalingen
        $this->db->query("
                    DELETE FROM payment 
                    WHERE member_id IN (" . implode(',', array_fill(0, count($memberIds), '?')) . ")
                ");
        foreach ($memberIds as $index => $memberId) {
          $this->db->bind($index + 1, $memberId);
        }
        $this->db->execute();

        // Verwijder contributies
        $this->db->query("
                    DELETE FROM contributions 
                    WHERE member_id IN (" . implode(',', array_fill(0, count($memberIds), '?')) . ")
                ");
        foreach ($memberIds as $index => $memberId) {
          $this->db->bind($index + 1, $memberId);
        }
        $this->db->execute();

        // Verwijder familieleden
        $this->db->query("
                    DELETE FROM family_members 
                    WHERE family_id = :family_id
                ");
        $this->db->bind(':family_id', $familyId);
        $this->db->execute();
      }

      // Update bookyear total_amount
      $this->updateBookyearTotalAmount($bookyear_id);

      // Verwijder familie
      $this->db->query("
                DELETE FROM family 
                WHERE id = :family_id
            ");
      $this->db->bind(':family_id', $familyId);
      $this->db->execute();

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log('Database error in deleteFamily: ' . $e->getMessage() . ' | FamilyId: ' . $familyId);
      return false;
    }
  }

  public function updateBookyearTotalAmount($bookyear_id)
  {
    $this->db->query("
            UPDATE bookyear 
            SET total_amount = COALESCE((
                SELECT SUM(amount) 
                FROM contributions 
                WHERE bookyear_id = :bookyear_id
            ), 0)
            WHERE id = :bookyear_id
        ");
    $this->db->bind(':bookyear_id', $bookyear_id, PDO::PARAM_INT);
    $this->db->execute();
  }
}
