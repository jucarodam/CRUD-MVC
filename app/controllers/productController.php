<?php

namespace app\controllers;

use app\models\mainModel;

class productController extends mainModel
{

    /*----------  Controlador registrar producto  ----------*/
    public function registrarProductoControlador()
    {

        # Capturando datos
        $code = $_POST['code'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];

        # validadando campos obligatorios
        if ($code == "" || $name == "" || $category == "" || $price == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Something went wrong, fill the fields",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->validarDatos("^(0|[1-9]\d*)(\.\d+)?", $price)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Something went wrong, enter the correct format for price",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando producto #
        $validarProducto = $this->ejecutarConsulta("SELECT code FROM product WHERE code ='$code'");
        if ($validarProducto->rowCount() > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Code already exists, please enter another code",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        }

        $product_datos_reg = [
            [
                "campo_nombre" => "code",
                "campo_marcador" => ":Code",
                "campo_valor" => $code,
            ],
            [
                "campo_nombre" => "name",
                "campo_marcador" => ":Name",
                "campo_valor" => $name,
            ],
            [
                "campo_nombre" => "idCategory",
                "campo_marcador" => ":Category",
                "campo_valor" => $category,
            ],
            [
                "campo_nombre" => "price",
                "campo_marcador" => ":Price",
                "campo_valor" => $price,
            ],
        ];

        $insertarProducto = $this->insertarDatos("product", $product_datos_reg);

        if ($insertarProducto->rowCount() == 1) {
            $alerta = [
                "tipo" => "confirmar",
                "titulo" => "Success",
                "texto" => "The product " . $name . " was successfully registered",
                "icono" => "success",
            ];
        } else {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Product could not be registered, please try again",
                "icono" => "error",
            ];
        }

        return json_encode($alerta);

    }

    /*----------  Controlador listar categorias  ----------*/
    public function listarCategoriasControlador()
    {

        $select = "";

        $consulta_categorias = "SELECT * FROM category";

        $datos = $this->ejecutarConsulta($consulta_categorias);
        $datos = $datos->fetchAll();

        $select .= '<select class="form-control" id="category" name="category" required>';

        foreach ($datos as $rows) {
            $select .= '<option value="' . $rows['idCategory'] . '">' . $rows['name'] . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    /*----------  Controlador listar productos  ----------*/
    public function listarProductoControlador()
    {

        $tabla = "";

        $consulta_datos = "SELECT product.*, category.name AS categoryName FROM product LEFT JOIN category ON category.idCategory = product.idCategory";

        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        $tabla .= '
    <div class="container">
        <table class="table text-center mt-5">
            <thead>
                <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Price</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th colspan="2" scope="col">Options</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
            ';

        foreach ($datos as $rows) {
            $tabla .= '
                <tr>
                    <td>' . $rows['code'] . '</td>
                    <td>' . $rows['name'] . '</td>
                    <td>' . $rows['categoryName'] . '</td>
                    <td>' . $rows['price'] . '</td>
                    <td>' . date("d-m-Y  h:i:s A", strtotime($rows['createdAt'])) . '</td>
                    <td>' . date("d-m-Y  h:i:s A", strtotime($rows['updatedAt'])) . '</td>
                    <td class="text-end">
                    <a href="' . APP_URL . 'updateProduct/' . $rows['idProduct'] . '/" class="btn btn-success">Update</a>
                    </td>
                    <td class="text-start">
                    <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/productAjax.php" method="POST" autocomplete="off" >
                    <input type="hidden" name="modulo_producto" value="eliminar">
                    <input type="hidden" name="idProduct" value="' . $rows['idProduct'] . '">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    </td>
                    </tr>';
        }
        $tabla .= '</tbody></table></div>';
        return $tabla;
    }

    /*----------  Controlador eliminar producto  ----------*/
    public function eliminarProductoControlador()
    {

        $id = $_POST['idProduct'];

        $eliminarProducto = $this->eliminarDatos("product", "idProduct", $id);

        if ($eliminarProducto->rowCount() == 1) {

            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Success",
                "texto" => "The product has been deleted",
                "icono" => "success",
            ];

        } else {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops",
                "texto" => "The product cannot be removed, please try again.",
                "icono" => "error",
            ];
        }

        return json_encode($alerta);
    }

    /*----------  Controlador actualizar producto  ----------*/
    public function actualizarProductoControlador()
    {

        $id = $_POST['idProduct'];

        # Verificando usuario #
        $datos = $this->ejecutarConsulta("SELECT * FROM product WHERE idProduct='$id'");
        if ($datos->rowCount() <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos encontrado el usuario en el sistema",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        } else {
            $datos = $datos->fetch();
        }

        # Capturando datos
        $code = $_POST['code'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];

        # validadando campos obligatorios
        if ($code == "" || $name == "" || $category == "" || $price == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Something went wrong, fill the fields",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando integridad de los datos
        if ($this->validarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $name)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Something went wrong, enter the correct format for name",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->validarDatos("^(0|[1-9]\d*)(\.\d+)?", $price)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Something went wrong, enter the correct format for price",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando producto #
        $validarProducto = $this->ejecutarConsulta("SELECT code FROM product WHERE code ='$code'");
        if ($validarProducto->rowCount() > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Oops...",
                "texto" => "Code already exists, please enter another code",
                "icono" => "error",
            ];
            return json_encode($alerta);
            exit();
        }

        $product_datos_up = [
            [
                "campo_nombre" => "code",
                "campo_marcador" => ":Code",
                "campo_valor" => $code,
            ],
            [
                "campo_nombre" => "name",
                "campo_marcador" => ":Name",
                "campo_valor" => $name,
            ],
            [
                "campo_nombre" => "idCategory",
                "campo_marcador" => ":Category",
                "campo_valor" => $category,
            ],
            [
                "campo_nombre" => "price",
                "campo_marcador" => ":Price",
                "campo_valor" => $price,
            ],
        ];

        $condicion = [
            "condicion_campo" => "idProduct",
            "condicion_marcador" => ":idProduct",
            "condicion_valor" => $id,
        ];

        if ($this->actualizarDatos("product", $product_datos_up, $condicion)) {

            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Usuario actualizado",
                "texto" => "Los datos del usuario " . $datos['usuario_nombre'] . " " . $datos['usuario_apellido'] . " se actualizaron correctamente",
                "icono" => "success",
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos podido actualizar los datos del usuario " . $datos['usuario_nombre'] . " " . $datos['usuario_apellido'] . ", por favor intente nuevamente",
                "icono" => "error",
            ];
        }

        return json_encode($alerta);
    }

}
