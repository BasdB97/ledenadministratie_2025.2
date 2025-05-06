<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<?php
// echo '<pre>';
// var_dump($data);
// echo '<br>';
// var_dump($_SESSION);
// echo '</pre>';
?>
<div class="px-6 py-8">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Families</h2>
    <a href="<?php echo URL_ROOT; ?>/family/addFamily"
      class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
      <i class="fas fa-plus mr-2"></i> Nieuwe Familie
    </a>
  </div>

  <p class="mb-6 text-gray-600">Op deze pagina kunt u een overzicht zien van alle families. U kunt nieuwe families toevoegen, bestaande families bekijken, bewerken of verwijderen. Klik op de details knop (oog) om de familie details te bekijken.</p>

  <?php flash('family_message'); ?>

  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-primary text-white">
          <tr>
            <th class="py-3 px-4 text-left w-1/6">Naam</th>
            <th class="py-3 px-4 text-left w-1/5">Straat + Huisnummer</th>
            <th class="py-3 px-4 text-left w-1/10">Postcode</th>
            <th class="py-3 px-4 text-left w-1/6">Plaats</th>
            <th class="py-3 px-4 text-left w-1/10">Aantal Leden</th>
            <th class="py-3 px-4 text-left w-1/6">Acties</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if (!empty($data['families'])) : ?>
            <?php foreach ($data['families'] as $family) : ?>
              <tr class="hover:bg-gray-100">
                <td class="py-3 px-4"><?php echo $family->name; ?></td>
                <td class="py-3 px-4"><?php echo $family->street; ?> <?php echo $family->house_number; ?></td>
                <td class="py-3 px-4"><?php echo $family->postal_code; ?></td>
                <td class="py-3 px-4"><?php echo $family->city; ?></td>
                <td class="py-3 px-4"><?php echo $family->member_count; ?></td>
                <td class="py-3 px-4">
                  <div class="flex space-x-2 whitespace-nowrap">
                    <a href="<?php echo URL_ROOT; ?>/family/familyDetails/<?php echo $family->id; ?>"
                      class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
                      title="Details">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/family/editFamily/<?php echo $family->id; ?>"
                      class="inline-flex items-center justify-center w-8 h-8 bg-primary text-white rounded hover:bg-primary-dark transition-colors"
                      title="Bewerken">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/family/deleteFamily/<?php echo $family->id; ?>"
                      class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
                      onclick="return confirm('Weet je zeker dat je deze familie wilt verwijderen? Alle leden worden ook verwijderd! Dit is een onomkeerbare actie.');"
                      title="Verwijderen">
                      <i class="fas fa-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="7" class="py-8 text-center text-red-500">Geen families gevonden</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>