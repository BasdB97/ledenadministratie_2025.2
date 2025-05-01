<?php
include_once APP_ROOT . '/views/includes/header.php';
// echo '<pre>';
// var_dump($_SESSION);
// echo '<br>';
// var_dump($data['familyMembers']);
// echo '</pre>';
?>

<!-- <h1>Dashboard Rol: <?php echo $_SESSION['user_role']; ?></h1>
<h2>Boekjaar: <?php echo $_SESSION['selectedYear']; ?></h2>
<h2>Actief: <?php echo $_SESSION['selectedYear'] == date('Y') ? 'Ja' : 'Nee'; ?> </h2> -->

<div class="flex flex-wrap gap-6">
  <?php
  $displayedFamilies = [];
  foreach ($data['familyMembers'] as $member):
    if (!in_array($member->family_id, $displayedFamilies)):
      $displayedFamilies[] = $member->family_id;
  ?>
      <div class="card bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-200 w-1/3">
        <div class="card-header bg-primary text-white py-3 px-4 font-bold text-lg">
          <i class="fa-solid fa-users mr-2"></i> Familie <?php echo $member->family_name; ?>
        </div>
        <div class="card-body p-5">
          <div class="mb-4 text-gray-700">
            <p class="flex items-center mb-2">
              <i class="fa-solid fa-location-dot mr-3 text-primary w-5 text-center"></i>
              <span><strong>Adres:</strong> <?php echo $member->street . ' ' . $member->house_number . ', ' . $member->postal_code . ' ' . $member->city . ' ' . $member->country; ?></span>
            </p>
            <p class="flex items-center mb-2">
              <i class="fa-solid fa-user-group mr-3 text-primary w-5 text-center"></i>
              <span><strong>Aantal leden:</strong> <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold"><?php echo $member->member_count; ?></span></span>
            </p>
            <p class="flex items-center">
              <i class="fa-solid fa-coins mr-3 text-primary w-5 text-center"></i>
              <span><strong>Openstaande contributie:</strong> <span class="text-red-600 font-bold"><?php echo number_format($member->total_contribution, 2, ',', '.'); ?></span></span>
            </p>
          </div>
          <div class="flex space-x-2 mt-5 pt-4 border-t border-gray-200">
            <button type="button" class="flex-1 bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-200 flex items-center justify-center">
              <i class="fa-solid fa-eye mr-2"></i> Bekijken
            </button>
            <button type="button" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition-colors duration-200 flex items-center justify-center">
              <i class="fa-solid fa-credit-card mr-2"></i> Betalen
            </button>
            <button type="button" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md transition-colors duration-200 flex items-center justify-center">
              <i class="fa-solid fa-trash mr-2"></i> Verwijderen
            </button>
          </div>
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php
include_once APP_ROOT . '/views/includes/footer.php';
?>