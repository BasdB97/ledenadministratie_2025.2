<?php
// if (isLoggedIn()) {
//   redirect('dashboard/index');
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              light: '#60a5fa',
              DEFAULT: '#3b82f6',
              dark: '#2563eb',
            },
            bgDark: '#1e3a8a',
            bgLight: '#f5f5f5',
            borderColor: '#4b72bf',
          }
        }
      }
    }
  </script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title><?php echo SITE_NAME; ?></title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-gray-100">
  <header class="bg-primary text-white shadow-md">
    <div class="container mx-auto px-4 py-3">
      <h1 class="text-2xl text-center font-bold"><?php echo SITE_NAME; ?></h1>
    </div>
  </header>

  <main class="flex-1 flex justify-center items-center overflow-y-auto p-5 bg-bgLight">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-lg shadow-xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-center mb-6 text-primary">Login</h2>
        <p class="text-center text-gray-600 mb-6">Log in om door te gaan</p>

        <?php if (!empty($data['error'])): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $data['error']; ?>
          </div>
        <?php endif; ?>

        <form action="<?php echo URL_ROOT; ?>/auth/login" method="post" class="space-y-6">
          <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Gebruikersnaam <span class="text-red-500">*</span></label>
            <input type="text" name="username" id="username"
              class="mt-1 block w-full rounded-md border-2 border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 <?php echo (!empty($data['username_err'])) ? 'border-red-500' : ''; ?>"
              required>
            <?php if (!empty($data['username_err'])): ?>
              <p class="mt-1 text-sm text-red-600"><?php echo $data['username_err']; ?></p>
            <?php endif; ?>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Wachtwoord <span class="text-red-500">*</span></label>
            <input type="password" name="password" id="password"
              class="mt-1 block w-full rounded-md border-2 border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>"
              required>
            <?php if (!empty($data['password_err'])): ?>
              <p class="mt-1 text-sm text-red-600"><?php echo $data['password_err']; ?></p>
            <?php endif; ?>
          </div>

          <div>
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition duration-150 ease-in-out">
              Inloggen
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="bg-primary text-white py-3 shadow-inner">
    <div class="container mx-auto px-4">
      <p class="text-center text-sm">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?> | Bas de Bruin</p>
    </div>
  </footer>
</body>

</html>