<?php include_once APP_ROOT . '/views/includes/header.php'; ?>

<div class="px-6 py-8">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">
      <i class="fas fa-users mr-2 text-primary"></i>
      Familie <?php echo $data['family']->name; ?> Bewerken
    </h2>
    <a href="<?php echo URL_ROOT; ?>/family/index"
      class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
      <i class="fas fa-arrow-left mr-2"></i> Terug naar Families
    </a>
  </div>

  <?php flash('family_message'); ?>

  <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
    <form action="<?php echo URL_ROOT; ?>/family/editFamily/<?php echo $data['family']->id; ?>" method="POST" class="space-y-6">
      <?php if (!empty($data['address_err'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
          <p><?php echo $data['address_err']; ?></p>
        </div>
      <?php endif; ?>

      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Familienaam <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name"
          class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                      focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                      <?php echo !empty($data['name_err']) ? 'border-red-500' : ''; ?>"
          value="<?php echo $data['family']->name; ?>">
        <?php if (!empty($data['name_err'])): ?>
          <p class="mt-1 text-sm text-red-500"><?php echo $data['name_err']; ?></p>
        <?php endif; ?>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="street" class="block text-sm font-medium text-gray-700">Straat <span class="text-red-500">*</span></label>
          <input type="text" name="street" id="street"
            class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                        focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                        <?php echo !empty($data['street_err']) ? 'border-red-500' : ''; ?>"
            value="<?php echo $data['family']->street; ?>">
          <?php if (!empty($data['street_err'])): ?>
            <p class="mt-1 text-sm text-red-500"><?php echo $data['street_err']; ?></p>
          <?php endif; ?>
        </div>
        <div>
          <label for="house_number" class="block text-sm font-medium text-gray-700">Huisnummer <span class="text-red-500">*</span></label>
          <input type="text" name="house_number" id="house_number"
            class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                        focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                        <?php echo !empty($data['house_number_err']) ? 'border-red-500' : ''; ?>"
            value="<?php echo $data['family']->house_number; ?>">
          <?php if (!empty($data['house_number_err'])): ?>
            <p class="mt-1 text-sm text-red-500"><?php echo $data['house_number_err']; ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="postal_code" class="block text-sm font-medium text-gray-700">Postcode <span class="text-red-500">*</span></label>
          <input type="text" name="postal_code" id="postal_code"
            class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                        focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                        <?php echo !empty($data['postal_code_err']) ? 'border-red-500' : ''; ?>"
            value="<?php echo $data['family']->postal_code; ?>">
          <?php if (!empty($data['postal_code_err'])): ?>
            <p class="mt-1 text-sm text-red-500"><?php echo $data['postal_code_err']; ?></p>
          <?php endif; ?>
        </div>
        <div>
          <label for="city" class="block text-sm font-medium text-gray-700">Plaats <span class="text-red-500">*</span></label>
          <input type="text" name="city" id="city"
            class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                        focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                        <?php echo !empty($data['city_err']) ? 'border-red-500' : ''; ?>"
            value="<?php echo $data['family']->city; ?>">
          <?php if (!empty($data['city_err'])): ?>
            <p class="mt-1 text-sm text-red-500"><?php echo $data['city_err']; ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div>
        <label for="country" class="block text-sm font-medium text-gray-700">Land <span class="text-red-500">*</span></label>
        <select name="country" id="country"
          class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                      focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                      <?php echo !empty($data['country_err']) ? 'border-red-500' : ''; ?>">
          <?php $currentCountry = $data['country'] ?? $data['family']->country; ?>
          <option value="Nederland" <?php echo $currentCountry == 'Nederland' ? 'selected' : ''; ?>>Nederland</option>
          <option value="België" <?php echo $currentCountry == 'België' ? 'selected' : ''; ?>>België</option>
          <option value="Duitsland" <?php echo $currentCountry == 'Duitsland' ? 'selected' : ''; ?>>Duitsland</option>
          <option value="Frankrijk" <?php echo $currentCountry == 'Frankrijk' ? 'selected' : ''; ?>>Frankrijk</option>
          <option value="Verenigd Koninkrijk" <?php echo $currentCountry == 'Verenigd Koninkrijk' ? 'selected' : ''; ?>>Verenigd Koninkrijk</option>
        </select>
        <?php if (!empty($data['country_err'])): ?>
          <p class="mt-1 text-sm text-red-500"><?php echo $data['country_err']; ?></p>
        <?php endif; ?>
      </div>

      <div class="flex justify-end space-x-4">
        <a href="<?php echo URL_ROOT; ?>/family/index"
          class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
          Annuleren
        </a>
        <button type="submit"
          class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
          <i class="fas fa-save mr-2"></i> Wijzigingen Opslaan
        </button>
      </div>
    </form>
  </div>
</div>

<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>