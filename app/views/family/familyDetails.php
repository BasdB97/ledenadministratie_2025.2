<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<?php
// echo '<pre>';
// var_dump($data);
// echo '</pre>';
?>
<div class="container mx-auto px-4 py-6">
  <h1 class="text-3xl font-bold mb-6">Familie Details</h1>

  <div class="bg-white rounded-lg shadow-md mb-6">
    <div class="bg-primary text-white px-6 py-4 rounded-t-lg">
      <h2 class="text-xl font-semibold">Familie <?php echo $data['family']->name; ?></h2>
    </div>
    <div class="p-6">
      <p class="mb-2"><span class="font-semibold">Naam:</span> <?php echo $data['family']->name; ?></p>
      <p class="mb-2"><span class="font-semibold">Adres:</span> <?php echo $data['family']->street; ?> <?php echo $data['family']->house_number; ?>, <?php echo $data['family']->postal_code; ?> <?php echo $data['family']->city; ?></p>
      <p class="mb-2"><span class="font-semibold">Openstaande Contributie:</span>
        <span class="<?php echo ($data['family']->total_contribution > 0) ? 'text-red-600' : 'text-green-600'; ?>">
          €<?php echo number_format($data['family']->total_contribution, 2, ',', '.'); ?>
        </span>
      </p>
    </div>
  </div>

  <div class="flex justify-between items-center mb-4">
    <h3 class="text-2xl font-semibold">Familieleden</h3>
    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md flex items-center">
      <i class="fas fa-plus mr-2"></i>
      Lid Toevoegen
    </button>
  </div>

  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leeftijd</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type Lid</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Openstaande Contributie</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Betaling</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php foreach ($data['members'] as $member) : ?>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap"><?php echo $member->first_name; ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?php echo calculateAge($member->date_of_birth); ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?php echo $member->member_type; ?></td>
              <td class="px-6 py-4 whitespace-nowrap">€ <?php echo number_format($member->outstanding_contribution, 2, ',', '.'); ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo ($member->outstanding_contribution > 0) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                  <?php echo ($member->outstanding_contribution > 0) ? 'Openstaand' : 'Voldaan'; ?>
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap space-x-2">
                <button class="bg-primary hover:bg-primary-dark text-white px-3 py-1 rounded-md text-sm">
                  Bewerken
                </button>
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm">
                  Verwijderen
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
// Helper function to calculate age from birth date
function calculateAge($birthDate)
{
  $today = new DateTime();
  $birth = new DateTime($birthDate);
  $age = $birth->diff($today)->y;
  return $age;
}

include_once APP_ROOT . '/views/includes/footer.php';
?>