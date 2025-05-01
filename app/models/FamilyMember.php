<?php

class FamilyMember
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Haalt alle familieleden op die een contributie hebben in het opgegeven boekjaar
   * 
   * @param int $bookyear_id Het ID van het boekjaar
   * @return array Array met familieleden
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
