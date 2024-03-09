<div class="container">

  <?php
use app\controllers\productController;

$insProducto = new productController();

echo $insProducto->listarProductoControlador();
?>

</div>