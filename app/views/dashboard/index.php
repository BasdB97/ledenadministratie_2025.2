<?php
include_once APP_ROOT . '/views/includes/header.php';
?>

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-gray-800">
      <i class="fa-solid fa-chart-line mr-2 text-primary"></i>
      Welkom bij het Contributie Dashboard
    </h1>
    <div class="text-sm text-gray-600">
      <span class="bg-primary text-white px-3 py-1 rounded-full">
        Rol: <?php echo $_SESSION['user_role']; ?>
      </span>
    </div>
  </div>

  <div class="flex items-center mb-6 text-gray-700">
    <div class="mr-6">
      <span class="font-semibold">Boekjaar:</span>
      <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-md ml-1">
        <?php echo $_SESSION['selectedYear']; ?>
      </span>
    </div>
    <div>
      <span class="font-semibold">Status:</span>
      <?php if ($_SESSION['selectedYear'] == date('Y')): ?>
        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-md ml-1">Actief</span>
      <?php else: ?>
        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-md ml-1">Niet actief</span>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
  <h2 class="text-xl font-bold text-gray-800 mb-4">
    <i class="fa-solid fa-users mr-2 text-primary"></i>
    Families Overzicht
  </h2>
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-primary text-white">
          <th class="py-3 px-4 font-semibold">Familie</th>
          <th class="py-3 px-4 font-semibold">Adres</th>
          <th class="py-3 px-4 font-semibold">Aantal Leden</th>
          <th class="py-3 px-4 font-semibold">Openstaande Contributie</th>
          <th class="py-3 px-4 font-semibold">Acties</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($data['families'])): ?>
          <tr>
            <td colspan="5" class="py-3 px-4 text-gray-600 text-center">Geen families gevonden voor dit boekjaar.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($data['families'] as $family): ?>
            <tr class="border-b border-gray-200 hover:bg-gray-50">
              <td class="py-3 px-4">
                <span class="flex items-center">
                  <?php echo htmlspecialchars($family->family_name); ?>
                </span>
              </td>
              <td class="py-3 px-4">
                <span class="flex items-center">
                  <?php echo htmlspecialchars($family->street . ' ' . $family->house_number . ', ' . $family->postal_code . ' ' . $family->city . ', ' . $family->country); ?>
                </span>
              </td>
              <td class="py-3 px-4">
                <span class="text-blue-800 px-2 py-1 font-bold">
                  <?php echo $family->member_count; ?>
                </span>
              </td>
              <td class="py-3 px-4">
                <span class="flex items-center">
                  <span class="<?php echo $family->total_contribution > 0 ? 'text-red-600' : 'text-green-600'; ?> font-bold">€ <?php echo number_format($family->total_contribution, 2, ',', '.'); ?></span>
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex space-x-2">
                  <button type="button" class="bg-primary hover:bg-primary-dark text-white py-1 px-3 rounded-md transition-colors duration-200 flex items-center">
                    <i class="fa-solid fa-eye mr-1"></i> Bekijken
                  </button>
                  <button type="button" class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded-md transition-colors duration-200 flex items-center">
                    <i class="fa-solid fa-credit-card mr-1"></i> Betalen
                  </button>
                  <button type="button" class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded-md transition-colors duration-200 flex items-center">
                    <i class="fa-solid fa-trash mr-1"></i> Verwijderen
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
include_once APP_ROOT . '/views/includes/footer.php';
?>