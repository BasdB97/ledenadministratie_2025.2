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

<body class="h-screen overflow-hidden flex flex-col bg-gray-100">
  <header class="bg-primary text-white shadow-md">
    <div class="container mx-auto px-4 py-3">
      <h1 class="text-2xl text-center font-bold"><?php echo SITE_NAME; ?></h1>
    </div>
  </header>

  <div class="flex flex-1 overflow-hidden">
    <nav class="w-64 bg-bgDark text-white flex-shrink-0 overflow-y-auto border-r border-borderColor">
      <ul class="py-4 space-y-1">
        <li>
          <a href="<?php echo URL_ROOT; ?>/dashboard/index"
            class="flex items-center px-4 py-2 <?php echo basename($_SERVER['SCRIPT_NAME']) == 'dashboard.php' ? 'bg-primary text-white' : 'text-gray-300 hover:bg-primary-dark hover:text-white'; ?> rounded-lg mx-2">
            <i class="fa-solid fa-house mr-3 w-5 text-center"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="<?php echo URL_ROOT; ?>/family/index"
            class="flex items-center px-4 py-2 <?php echo basename($_SERVER['SCRIPT_NAME']) == 'family.php' ? 'bg-primary text-white' : 'text-gray-300 hover:bg-primary-dark hover:text-white'; ?> rounded-lg mx-2">
            <i class="fa-solid fa-users mr-3 w-5 text-center"></i>
            <span>Families</span>
          </a>
        </li>

        <?php if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'treasurer'): ?>
          <li>
            <a href="<?php echo URL_ROOT; ?>/contributions/index"
              class="flex items-center px-4 py-2 <?php echo basename($_SERVER['SCRIPT_NAME']) == 'contributions.php' ? 'bg-primary text-white' : 'text-gray-300 hover:bg-primary-dark hover:text-white'; ?> rounded-lg mx-2">
              <i class="fa-solid fa-coins mr-3 w-5 text-center"></i>
              <span>Contributie</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'treasurer'): ?>
          <li>
            <a href="<?php echo URL_ROOT; ?>/bookyear/index"
              class="flex items-center px-4 py-2 <?php echo basename($_SERVER['SCRIPT_NAME']) == 'bookyear.php' ? 'bg-primary text-white' : 'text-gray-300 hover:bg-primary-dark hover:text-white'; ?> rounded-lg mx-2">
              <i class="fa-solid fa-coins mr-3 w-5 text-center"></i>
              <span>Boekjaar</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['user_role'] == 'admin'): ?>
          <li>
            <a href="<?php echo URL_ROOT; ?>/membertype/index"
              class="flex items-center px-4 py-2 <?php echo basename($_SERVER['SCRIPT_NAME']) == 'member-type.php' ? 'bg-primary text-white' : 'text-gray-300 hover:bg-primary-dark hover:text-white'; ?> rounded-lg mx-2">
              <i class="fa-solid fa-id-card mr-3 w-5 text-center"></i>
              <span>Soort lid</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['user_role'] == 'admin'): ?>
          <li>
            <a href="<?php echo URL_ROOT; ?>/users/index"
              class="flex items-center px-4 py-2 <?php echo basename($_SERVER['SCRIPT_NAME']) == 'users.php' ? 'bg-primary text-white' : 'text-gray-300 hover:bg-primary-dark hover:text-white'; ?> rounded-lg mx-2">
              <i class="fa-solid fa-user-pen mr-3 w-5 text-center"></i>
              <span>Gebruikers</span>
            </a>
          </li>
        <?php endif; ?>
        <li>
          <a href="<?php echo URL_ROOT; ?>/auth/logout"
            class="flex items-center px-4 py-2 text-gray-300 hover:bg-red-700 hover:text-white rounded-lg mx-2 mt-4">
            <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i>
            <span>Uitloggen</span>
          </a>
        </li>
      </ul>
    </nav>

    <main class="flex-1 overflow-y-auto p-5 bg-bgLight">
      <!-- Main content will be included here -->