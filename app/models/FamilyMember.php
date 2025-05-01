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
               (SELECT COUNT(*) FROM family_members fm2 WHERE fm2.family_id = fm.family_id) AS member_count,
               (SELECT SUM(c2.amount) 
                FROM contributions c2
                INNER JOIN family_members fm3 ON c2.member_id = fm3.id
                WHERE fm3.family_id = fm.family_id 
                AND c2.bookyear_id = :bookyear_id) AS total_contribution
        FROM family_members fm
        INNER JOIN contributions c ON fm.id = c.member_id
        INNER JOIN family f ON fm.family_id = f.id
        WHERE c.bookyear_id = :bookyear_id
    ");
    $this->db->bind(':bookyear_id', $bookyear_id);
    return $this->db->resultSet();
  }
}
