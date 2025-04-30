<?php
include_once APP_ROOT . '/views/includes/header.php';
// echo '<pre>';
// var_dump($_SESSION);
// echo '<br>';
// var_dump($data);
// echo '</pre>';
?>

<h1>Dashboard Rol: <?php echo $_SESSION['user_role']; ?></h1>
<h2>Boekjaar: <?php echo $_SESSION['selectedYear']; ?></h2>
<h2>Actief: <?php echo $_SESSION['selectedYear'] == date('Y') ? 'Ja' : 'Nee'; ?> </h2>

<?php
include_once APP_ROOT . '/views/includes/footer.php';
?>