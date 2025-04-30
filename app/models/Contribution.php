<?php

class Contribution
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getContributionByMember($memberId, $bookyearId)
  {
    $this->db->query("SELECT * FROM contribution WHERE family_member = :member_id AND bookyear = :bookyear_id");
    $this->db->bind(':member_id', $memberId);
    $this->db->bind(':bookyear_id', $bookyearId);
    return $this->db->single();
  }

  public function calculateContribution($age, $memberTypeId, $bookyearId)
  {
    // Get base contribution from bookyear
    $this->db->query("SELECT contribution FROM bookyear WHERE id = :bookyear_id");
    $this->db->bind(':bookyear_id', $bookyearId);
    $bookyear = $this->db->single();
    $baseContribution = $bookyear->contribution;

    // Get discount percentage from member type
    $this->db->query("SELECT discount_percentage FROM member_type WHERE id = :member_type_id");
    $this->db->bind(':member_type_id', $memberTypeId);
    $memberType = $this->db->single();
    $discount = $memberType->discount_percentage;

    // Calculate final contribution
    $discountAmount = $baseContribution * ($discount / 100);
    $finalContribution = $baseContribution - $discountAmount;

    return $finalContribution;
  }

  public function updateContribution($contributionId, $age, $amount)
  {
    $this->db->query("UPDATE contribution SET age = :age, amount = :amount WHERE id = :id");
    $this->db->bind(':age', $age);
    $this->db->bind(':amount', $amount);
    $this->db->bind(':id', $contributionId);
    return $this->db->execute();
  }

  public function createContribution($memberId, $bookyearId, $age, $memberTypeId, $amount)
  {
    $this->db->query("INSERT INTO contribution (family_member, bookyear, age, member_type, amount) 
                      VALUES (:family_member, :bookyear, :age, :member_type, :amount)");
    $this->db->bind(':family_member', $memberId);
    $this->db->bind(':bookyear', $bookyearId);
    $this->db->bind(':age', $age);
    $this->db->bind(':member_type', $memberTypeId);
    $this->db->bind(':amount', $amount);
    return $this->db->execute();
  }
}
