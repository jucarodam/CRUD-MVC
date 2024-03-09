<?php

require_once "../../config/app.php";
require_once "../../autoload.php";

use app\controllers\productController;

if (isset($_POST['modulo_producto'])) {

    $insProducto = new productController();

    if ($_POST['modulo_producto'] == "registrar") {
        echo $insProducto->registrarProductoControlador();
    }

    if ($_POST['modulo_producto'] == "eliminar") {
        echo $insProducto->eliminarProductoControlador();
    }

} else {
    header("Location: " . APP_URL . "productList/");
}
