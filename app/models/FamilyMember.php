<?php

class FamilyMember
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Retrieves all family members who have a contribution in the specified book year
   * 
   * This method fetches family members along with their family information and calculates:
   * - The total number of members in each family
   * - The total outstanding contribution amount for each family
   * 
   * @param int $bookyear_id The ID of the book year to filter contributions by
   * @return array An array of family member objects with additional family data
   */
  public function getMembersWithContributionsByBookyear($bookyear_id)
  {
    $this->db->query("
        SELECT fm.*, 
               f.name AS family_name,
               f.street,
               f.house_number,
               f.postal_code,
               f.city,
               f.country,
               mt.description AS member_type,
               mt.discount_percentage,
               (100 * (1 - mt.discount_percentage / 100)) AS outstanding_contribution,
               (SELECT COUNT(*) FROM family_members fm2 WHERE fm2.family_id = fm.family_id) AS member_count,
               (SELECT SUM(c2.amount) 
                FROM contributions c2
                INNER JOIN family_members fm3 ON c2.member_id = fm3.id
                WHERE fm3.family_id = fm.family_id 
                AND c2.bookyear_id = :bookyear_id) AS total_contribution
        FROM family_members fm
        INNER JOIN contributions c ON fm.id = c.member_id
        INNER JOIN family f ON fm.family_id = f.id
        INNER JOIN member_type mt ON fm.member_type_id = mt.id
        WHERE c.bookyear_id = :bookyear_id
    ");
    $this->db->bind(':bookyear_id', $bookyear_id);
    return $this->db->resultSet();
  }

  public function addFamilyMemberWithContribution($data)
  {
    try {
      // Start transactie
      $this->db->beginTransaction();

      // Voeg familielid toe
      $this->db->query("
            INSERT INTO family_members (first_name, date_of_birth, member_type_id, family_id)
            VALUES (:first_name, :date_of_birth, :member_type_id, :family_id)
        ");
      $this->db->bind(':first_name', $data['first_name']);
      $this->db->bind(':date_of_birth', $data['date_of_birth']);
      $this->db->bind(':member_type_id', $data['member_type_id']);
      $this->db->bind(':family_id', $data['family_id']);
      $this->db->execute();

      // Haal het nieuwe lid-ID op
      $memberId = $this->db->lastInsertId();

      // Voeg contributie toe
      $this->db->query("
            INSERT INTO contributions (member_id, age, amount, member_type, bookyear_id)
            VALUES (:member_id, :age, :amount, :member_type, :bookyear_id)
        ");
      $this->db->bind(':member_id', $memberId);
      $this->db->bind(':age', $data['age']);
      $this->db->bind(':amount', $data['contribution_amount']);
      $this->db->bind(':member_type', $data['member_type_id']);
      $this->db->bind(':bookyear_id', $data['bookyear_id'], PDO::PARAM_INT);
      $this->db->execute();

      // Update boekjaar totaalbedrag
      $this->db->query("
                UPDATE bookyear 
                SET total_amount = COALESCE(total_amount, 0) + :amount 
                WHERE id = :bookyear_id
            ");
      $this->db->bind(':amount', $data['contribution_amount']);
      $this->db->bind(':bookyear_id', $data['bookyear_id'], PDO::PARAM_INT);
      $this->db->execute();

      // Commit transactie
      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log('Database error in addFamilyMemberWithContribution: ' . $e->getMessage());
      return false;
    }
  }

  public function getDiscountPercentage($memberTypeId)
  {
    $this->db->query("SELECT discount_percentage FROM member_type WHERE id = :member_type_id");
    $this->db->bind(':member_type_id', $memberTypeId);
    $result = $this->db->single();
    return $result ? $result->discount_percentage : 0;
  }

  public function getFamilyMemberById($memberId)
  {
    $this->db->query("
      SELECT fm.*, f.name as last_name, f.street, f.house_number, f.postal_code, f.city, f.country 
      FROM family_members fm
      JOIN family f ON fm.family_id = f.id
      JOIN contributions c ON fm.id = c.member_id
      WHERE fm.id = :id
    ");
    $this->db->bind(':id', $memberId);
    return $this->db->single();
  }

  public function updateFamilyMember($data)
  {
    try {
      $this->db->beginTransaction();

      // Haal oude contributiebedrag op
      $this->db->query("
                SELECT amount 
                FROM contributions 
                WHERE member_id = :member_id AND bookyear_id = :bookyear_id
            ");
      $this->db->bind(':member_id', $data['id']);
      $this->db->bind(':bookyear_id', $data['bookyear_id'], PDO::PARAM_INT);
      $oldContribution = $this->db->single();
      $oldAmount = $oldContribution ? $oldContribution->amount : 0;

      // Update familielid
      $this->db->query("
                UPDATE family_members 
                SET first_name = :first_name, 
                    date_of_birth = :date_of_birth, 
                    member_type_id = :member_type_id 
                WHERE id = :id
            ");
      $this->db->bind(':first_name', $data['first_name']);
      $this->db->bind(':date_of_birth', $data['date_of_birth']);
      $this->db->bind(':member_type_id', $data['member_type_id']);
      $this->db->bind(':id', $data['id']);
      $this->db->execute();

      // Update contributie
      $this->db->query("
                UPDATE contributions 
                SET age = :age, 
                    amount = :amount, 
                    member_type = :member_type 
                WHERE member_id = :member_id 
                AND bookyear_id = :bookyear_id
            ");
      $this->db->bind(':age', $data['age']);
      $this->db->bind(':amount', $data['contribution_amount']);
      $this->db->bind(':member_type', $data['member_type_id']);
      $this->db->bind(':member_id', $data['id']);
      $this->db->bind(':bookyear_id', $data['bookyear_id'], PDO::PARAM_INT);
      $this->db->execute();

      // Update boekjaar totaalbedrag (verschil)
      $amountDifference = $data['contribution_amount'] - $oldAmount;
      if ($amountDifference != 0) {
        $this->db->query("
                    UPDATE bookyear 
                    SET total_amount = COALESCE(total_amount, 0) + :amount 
                    WHERE id = :bookyear_id
                ");
        $this->db->bind(':amount', $amountDifference);
        $this->db->bind(':bookyear_id', $data['bookyear_id'], PDO::PARAM_INT);
        $this->db->execute();
      }

      // Commit transactie
      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log('Database error in updateFamilyMember: ' . $e->getMessage());
      return false;
    }
  }

  public function deleteFamilyMember($memberId)
  {
    try {
      $this->db->beginTransaction();

      // Haal contributiebedrag op voor het actieve boekjaar
      $this->db->query("
                SELECT c.amount, c.bookyear_id 
                FROM contributions c 
                WHERE c.member_id = :member_id
            ");
      $this->db->bind(':member_id', $memberId);
      $contribution = $this->db->single();
      $amount = $contribution ? $contribution->amount : 0;
      $bookyearId = $contribution ? $contribution->bookyear_id : null;

      // Verwijder betalingen
      $this->db->query("DELETE FROM payment WHERE member_id = :member_id");
      $this->db->bind(':member_id', $memberId);
      $this->db->execute();

      // Verwijder contributies
      $this->db->query("DELETE FROM contributions WHERE member_id = :member_id");
      $this->db->bind(':member_id', $memberId);
      $this->db->execute();

      // Update boekjaar totaalbedrag
      if ($amount > 0 && $bookyearId) {
        $this->db->query("
                    UPDATE bookyear 
                    SET total_amount = COALESCE(total_amount, 0) - :amount 
                    WHERE id = :bookyear_id
                ");
        $this->db->bind(':amount', $amount);
        $this->db->bind(':bookyear_id', $bookyearId, PDO::PARAM_INT);
        $this->db->execute();
      }

      // Verwijder familielid
      $this->db->query("DELETE FROM family_members WHERE id = :id");
      $this->db->bind(':id', $memberId);
      $this->db->execute();

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log('Database error in deleteFamilyMember: ' . $e->getMessage() . ' | MemberId: ' . $memberId);
      return false;
    }
  }
}
