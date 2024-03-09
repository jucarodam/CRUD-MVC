<div class="container mt-5">
  <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productAjax.php" method="POST" autocomplete="off">

    <input type="hidden" name="modulo_producto" value="actualizar">
    <input type="hidden" name="idProduct" value="">
    <!-- Text input -->
    <div class="mb-4">
      <label class="form-label" for="code">Code</label>
      <input type="text" id="code" name="code" class="form-control" placeholder="Product Code" required />
    </div>

    <!-- Text input -->
    <div class="mb-4">
      <label class="form-label" for="name">Name</label>
      <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" required />
    </div>

    <!-- Select -->
    <div class="mb-4">
      <label class="form-label" for="category">Category</label>
      <?php
use app\controllers\productController;

$insProducto = new productController();

echo $insProducto->listarCategoriasControlador();
?>
    </div>

    <!-- Text input -->
    <div class="mb-4">
      <label class="form-label" for="price">Price</label>
      <input type="text" id="price" name="price" class="form-control" placeholder="Product Price"
        pattern="^(0|[1-9]\d*)(\.\d+)?" required />
    </div>

    <!-- Submit button -->
    <input type="submit" class="btn btn-primary btn-block mb-4" value="Submit" />
  </form>
</div>