<?php include_once APP_ROOT . '/views/includes/header.php'; ?>

<?php
echo '<pre>';
var_dump($data);
echo '</pre>';
?>

<div class="container mx-auto px-4 mt-8">
  <h2 class="text-2xl font-bold text-gray-800 mb-4">Familielid toevoegen</h2>
  <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-blue-600 text-white px-4 py-3">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
        </svg>
        <span>Nieuw familielid gegevens</span>
      </div>
    </div>
    <?php flash('family_member_message'); ?>

    <div class="p-6 bg-gray-50 border border-gray-200">
      <form action="<?php echo URL_ROOT; ?>/familyMember/addFamilyMember/<?php echo $data['family_id']; ?>" method="post">
        <div class="mb-4">
          <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Voornaam</label>
          <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            id="first_name" name="first_name" required>
          <?php if (!empty($data['first_name_err'])): ?>
            <p class="mt-1 text-sm text-red-500"><?php echo $data['first_name_err']; ?></p>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Achternaam</label>
          <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-200 cursor-not-allowed"
            id="last_name" name="last_name"
            value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>" readonly disabled>
          <?php if (!empty($data['last_name_err'])): ?>
            <p class="mt-1 text-sm text-red-500"><?php echo $data['last_name_err']; ?></p>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Geboortedatum</label>
          <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            id="date_of_birth" name="date_of_birth" required>
          <?php if (!empty($data['date_of_birth_err'])): ?>
            <p class="mt-1 text-sm text-red-500"><?php echo $data['date_of_birth_err']; ?></p>
          <?php endif; ?>
        </div>

        <div class="flex justify-end space-x-3">
          <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Toevoegen
          </button>
          <a href="<?php echo URL_ROOT; ?>/family/familyDetails/<?php echo $data['family_id']; ?>"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
            Annuleren
          </a>
        </div>
      </form>
    </div>
  </div>
</div>



<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>