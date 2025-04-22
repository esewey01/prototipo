<?php
require_once('../Model/Conexion.php');
require_once('Constants.php');

class ProductoController {
    private $con;
    private $usuario;
    private $password;
    
    public function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $this->con = new Conexion();
        $this->usuario = $_POST['usuario'] ?? $_GET['usuario'] ?? null;
        $this->password = $_POST['password'] ?? $_GET['password'] ?? null;
    }
    
    public function handleRequest() {
        try {
            if (isset($_POST['nuevo_Producto'])) {
                $this->crearProducto();
            } elseif (isset($_GET['idborrar'])) {
                $this->eliminarProducto();
            } elseif (isset($_POST['update_producto'])) {
                $this->actualizarProducto();
            }
            
            $this->redirectToProductList();
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    private function crearProducto() {
        $datosProducto = $this->validarDatosProducto();
        $imagen = $this->procesarImagen('userfile', 'fotoproducto/default.jpg');
        
        $resultado = $this->con->registerNewProducto(
            $imagen,
            $datosProducto['codigo'],
            $datosProducto['nombre_producto'],
            $datosProducto['cantidad'],
            $datosProducto['fecha_registro'],
            $datosProducto['precio_venta'],
            $datosProducto['categoria'],
            null, // proveedor
            $datosProducto['precio_compra']
        );
        
        if (!$resultado) {
            throw new Exception("Error al registrar el producto");
        }
        
        $this->setMensajeExito("Se registró un nuevo producto correctamente");
    }
    
    private function actualizarProducto() {
        $datosProducto = $this->validarDatosProducto();
        $idProducto = $_POST['id_producto'];
        $imagenActual = $_POST['imagen'];
        
        $imagen = $this->procesarImagen('userfileEdit', $imagenActual);
        
        $resultado = $this->con->updateProducto(
            $datosProducto['id_categoria'],
            $datosProducto['codigo'],  
            $datosProducto['nombre_producto'],
            $datosProducto['descripcion'],
            $datosProducto['cantidad'],
            $datosProducto['precio_venta'],
            $datosProducto['precio_compra'],
            $idProducto  // Este parámetro va al final, no al inicio
        );
        
        if (!$resultado) {
            throw new Exception("Error al actualizar el producto");
        }
        
        $this->setMensajeExito("Se actualizaron los datos del producto correctamente");
    }
    
    private function eliminarProducto() {
        $idProducto = $_GET['idborrar'];
        
        $resultado = $this->con->deleteProduct($idProducto);
        
        if (!$resultado) {
            throw new Exception("Error al eliminar el producto");
        }
        
        $this->setMensajeExito("Se eliminó el producto correctamente", "alert-success");
    }
    
    private function validarDatosProducto() {/*
        $requiredFields = [
            'id_categoria', 'codigo', 'nombre_producto', 'descripcion', 
            'cantidad', 'precio_venta', 'precio_compra'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }
        */
        return [
            'id_categoria' => $_POST['id_categoria'],
            'codigo' => $_POST['codigo'],
            'nombre_producto' => $_POST['nombre_producto'],
            'descripcion' => $_POST['descripcion'],
            'cantidad' => $_POST['cantidad'],
            'precio_venta' => $_POST['precio_venta'],
            'precio_compra' => $_POST['precio_compra']
        ];
    }
    
    private function procesarImagen($inputName, $defaultImage) {
        if (empty($_FILES[$inputName]['name'])) {
            return $defaultImage;
        }
        
        $ruta = "fotoproducto/";
        $archivo = $_FILES[$inputName];
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreUnico = uniqid() . '.' . $extension;
        $destino = $ruta . $nombreUnico;
        
        // Validar tipo y tamaño
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($archivo['type'], $permitidos) || $archivo['size'] > 5000000) {
            throw new Exception("Solo se permiten imágenes JPG, PNG o GIF menores a 5MB");
        }
        
        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            throw new Exception("Error al subir la imagen");
        }
        
        return $destino;
    }
    
    private function setMensajeExito($mensaje, $tipo = "alert-success") {
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['alerta'] = "alert $tipo";
    }
    
    private function redirectToProductList() {
        header("Location: ProductoController.php");
        exit();
    }
    
    private function handleError($mensaje) {
        $_SESSION['error'] = $mensaje;
        $this->redirectToProductList();
    }
}

// Uso del controlador
$controller = new ProductoController();
$controller->handleRequest();