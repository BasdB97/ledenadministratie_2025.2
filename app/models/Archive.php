<?php

class Archive
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function archiveContributions($activeBookyearId)
  {
    // Get all contributions from previous years
    $this->db->query("SELECT * FROM contribution WHERE bookyear != :bookyear_id");
    $this->db->bind(':bookyear_id', $activeBookyearId);
    $contributions = $this->db->resultSet();

    // Start transaction
    $this->db->beginTransaction();

    try {
      // Move each contribution to archive
      foreach ($contributions as $contribution) {
        // Add to archive
        $this->db->query("INSERT INTO archive (age, family_member, member_type, amount, bookyear) 
                          VALUES (:age, :family_member, :member_type, :amount, :bookyear)");
        $this->db->bind(':age', $contribution->age);
        $this->db->bind(':family_member', $contribution->family_member);
        $this->db->bind(':member_type', $contribution->member_type);
        $this->db->bind(':amount', $contribution->amount);
        $this->db->bind(':bookyear', $contribution->bookyear);
        $this->db->execute();

        // Delete from contributions
        $this->db->query("DELETE FROM contribution WHERE id = :id");
        $this->db->bind(':id', $contribution->id);
        $this->db->execute();
      }

      // Commit transaction
      $this->db->commit();
      return true;
    } catch (Exception $e) {
      // Rollback transaction on error
      $this->db->rollBack();
      return false;
    }
  }

  public function hasContributionsFromOtherYears($activeBookyearId)
  {
    $this->db->query("SELECT COUNT(*) as count FROM contribution WHERE bookyear != :bookyear_id");
    $this->db->bind(':bookyear_id', $activeBookyearId);
    $result = $this->db->single();
    return $result->count > 0;
  }
}
