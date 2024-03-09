<?php
require_once "./config/app.php";
require_once "./autoload.php";

if (isset($_GET['views'])) {
    $url = explode("/", $_GET['views']);
} else {
    $url = ["productList"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once "./app/views/inc/head.php"?>
</head>

<body>

  <?php
use app\controllers\viewsController;

$viewsController = new viewsController();
$vista = $viewsController->obtenerVistasControlador($url[0]);

if ($vista == "404") {
    require_once "./app/views/content/" . $vista . "-view.php";
} else {
    require_once "./app/views/inc/navbar.php";
    require_once $vista;
}

?>


  <script src="<?php echo APP_URL; ?>app/views/js/ajax.js"></script>
</body>

</html>