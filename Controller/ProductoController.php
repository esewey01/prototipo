<?php
session_start();
require_once('../Model/Conexion.php');
require('Constants.php');
require_once('../public/tcpdf/tcpdf.php');

class ProductoController
{
    private $con;
    private $urlViews;
    private $id_usuario;


    public function __construct()
    {
        $this->con = new Conexion();
        $this->urlViews = URL_VIEWS;
        $this->id_usuario = $_SESSION['usuario']['id_usuario'];


        // Verificar sesión
        if (!isset($_SESSION['usuario']['login'])) {
            header("Location: /Prototipo/index.php");
            exit();
        }
    }

    public function index()
    {
        try {
            //MENSAJES DE ERROR
            $error = $_SESSION['error'] ?? '';
            $mensaje = $_SESSION['mensaje'] ?? '';
            $alerta = $_SESSION['alerta'] ?? '';
            $urlViews = URL_VIEWS;

            // Datos del usuario logueado
            $usuario = $_SESSION['usuario']['login'];
            $password = $_SESSION['usuario']['password'];

            $id_rol = $_SESSION['usuario']['rol']['id_rol'];
            $rol_usuario = $_SESSION['usuario']['rol']['nombre_rol'];

            // Obtener productos según el rol
            if ($id_rol == 2) { // Vendedor
                $productos = $this->con->getProductosByVendedor($this->id_usuario);
            } else { // Admin/Super User
                $productos = $this->con->getAllProductosWithVendedor();
            }

            $reportesProductos = [];
            foreach ($productos as $producto) {
                if ($producto['estado'] == 'INACTIVO') {
                    $reportesProductos[$producto['id_producto']] = $this->con->getReporteByProducto($producto['id_producto']);
                }
            }


            // Obtener categorías para los formularios
            $categorias = $this->con->getAllCategorias();

            // Preparar datos para la vista
            $data = [
                'productos' => $productos,
                'categorias' => $categorias,
                'urlViews' => $this->urlViews,
                'userLogueado' => $_SESSION['usuario']['nombre'] ?? 'Usuario',
                'imageUser' => $_SESSION['usuario']['foto'] ?? 'default.png',
                'esAdministrador' => (strtolower($rol_usuario) === 'administrador'),
                'esVendedor' => (strtolower($rol_usuario) === 'vendedor'),
                'mensaje' => $_SESSION['mensaje'] ?? null,
                'alerta' => $_SESSION['alerta'] ?? null,
                'reportesProductos' => $reportesProductos
            ];


            // Cargar vista
            require("../Views/ProductoViews.php");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al cargar productos: " . $e->getMessage();
            header("Location: Error.php");
            exit();
        }
    }

    //METODO PARA CAMBIAR EL ESTADO DEL PRODUCTO
    public function cambiarEstado()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            $id_producto = $_POST['id_producto'] ?? null;
            $nuevo_estado = $_POST['nuevo_estado'] ?? null;
            $comentarios = $_POST['comentarios'] ?? '';

            if (!$id_producto || !$nuevo_estado) {
                throw new Exception("Datos incompletos");
            }

            // Verificar permisos (solo vendedor puede marcar como AGOTADO)
            $id_rol = $_SESSION['usuario']['rol']['id_rol'];
            if ($nuevo_estado == 'AGOTADO' && $id_rol != 2) {
                throw new Exception("No tienes permisos para esta acción");
            }

            // Cambiar estado del producto
            $sql = "UPDATE PRODUCTOS SET estado = ? WHERE id_producto = ?";
            $resultado = $this->con->executeNonQuery($sql, [$nuevo_estado, $id_producto]);

            if (!$resultado) {
                throw new Exception("No se pudo actualizar el estado del producto");
            }

            // Si es un admin que marca como INACTIVO, crear reporte
            if ($nuevo_estado == 'INACTIVO' && $id_rol == 1) {
                $id_vendedor = $this->con->idVendedor($id_producto);
                $this->con->newReportforUser(
                    $id_producto,
                    $id_vendedor,
                    $_SESSION['usuario']['id_usuario'],
                    'Producto desactivado por administrador',
                    $comentarios,
                    'Producto desactivado'
                );
            }

            $_SESSION['mensaje'] = "Estado del producto actualizado correctamente";
            $_SESSION['alerta'] = "alert-success";
            header("Location: ProductoController.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['alerta'] = "alert-danger";
            header("Location: ProductoController.php");
            exit();
        }
    }

    //FUNCION ORIENTADA A GUARDAR UN NUEVO PRODUCTO
    public function guardar()
    {
        try {
            // Validar método de envío
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }


            $requiredFields = ['id_categoria', 'codigo', 'nombre_producto', 'descripcion', 'cantidad', 'pventa', 'pcompra'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            $verificarProducto = $this->con->verificarProducto(
                $_SESSION['usuario']['id_usuario'],
                $_POST['codigo'],
                $_POST['nombre_producto']
            );
            if ($verificarProducto) {
                throw new Exception("Producto ya existente ");
            }



            // Procesar imagen
            $imagen = 'fotoproducto/default.jpg';
            if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
                $extension = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension;
                $rutaDestino = '../Views/fotoproducto/' . $nombreArchivo;

                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $rutaDestino)) {
                    $imagen = 'fotoproducto/' . $nombreArchivo;
                }
            }


            // Insertar producto
            $resultado = $this->con->newProduct(
                $_SESSION['usuario']['id_usuario'],
                $_POST['id_categoria'],
                $_POST['codigo'],
                $_POST['nombre_producto'],
                $_POST['descripcion'],
                $_POST['cantidad'],
                $_POST['pventa'],
                $_POST['pcompra'],
                $imagen
            );


            if ($resultado) {
                $_SESSION['mensaje'] = "Producto registrado correctamente";
                $_SESSION['alerta'] = "alert-success";
            } else {
                throw new Exception("No se pudo registrar el producto");
            }

            header("Location: ProductoController.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al guardar: " . $e->getMessage();
            $_SESSION['alerta'] = "alert-danger";
            //$_SESSION['mensaje'] = $e->getMessage();
            header("Location: ProductoController.php");
            exit();
        }
    }
    //FUNCION PARA EDITAR UN PRODUCTPO
    public function editar()
{
    try {
        // Validar método de envío
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception("Método no permitido");
        }

        /*
        $requiredFields = ['id_producto', 'id_categoria', 'codigo', 'nombre_producto', 'descripcion', 'cantidad', 'pventa', 'pcompra'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }

        // Verificar si el producto ya existe
        $verificarProducto = $this->con->verificarProducto(
            $_SESSION['usuario']['id_usuario'],
            $_POST['codigo'],
            $_POST['nombre_producto']
        );
        if ($verificarProducto) {
            throw new Exception("Producto ya existente");
        }*/

        // Procesar imagen
        $imagen = $_POST['imagen']; // Valor predeterminado si no se sube una nueva imagen
        if (isset($_FILES['userfileEdit']) && $_FILES['userfileEdit']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['userfileEdit']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid() . '.' . $extension;
            $rutaDestino = '../Views/fotoproducto/' . $nombreArchivo;

            if (move_uploaded_file($_FILES['userfileEdit']['tmp_name'], $rutaDestino)) {
                $imagen = 'fotoproducto/' . $nombreArchivo;
            }
        }

        error_log("ID del producto: " . $_POST['id_producto']);
        // Actualizar producto
        $resultado = $this->con->updateProducto(
            $_POST['id_categoria'],
            $_POST['codigo'],
            $_POST['nombre_producto'],
            $_POST['descripcion'],
            $_POST['cantidad'],
            $_POST['precio_venta'],
            $_POST['precio_compra'],
            $imagen,
            $_POST['id_producto']
        );

        if ($resultado) {
            $_SESSION['mensaje'] = "Producto actualizado correctamente";
            $_SESSION['alerta'] = "alert-success";
        } else {
            throw new Exception("No se pudo actualizar el producto");
        }

        header("Location: ProductoController.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error al actualizar: " . $e->getMessage();
        $_SESSION['alerta'] = "alert-danger";
        header("Location: ProductoController.php");
        exit();
    }
}
    //FUNCION ORIENTADA A EXPORTAR A PDF
    public function exportarPDF()
    {
        try {
            $id_rol = $_SESSION['usuario']['rol']['id_rol'];

            // Obtener productos según el rol
            if ($id_rol == 2) { // Vendedor
                $productos = $this->con->getProductosByVendedor($this->id_usuario);
                $titulo = "MIS PRODUCTOS";
            } else { // Admin/Super User
                $productos = $this->con->getAllProductosWithVendedor();
                $titulo = "TODOS LOS PRODUCTOS DEL SISTEMA";
            }

            // Crear nuevo PDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // Configuración del documento
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('UPIICSA FOOD');
            $pdf->SetTitle('Reporte de Productos');
            $pdf->SetSubject('Reporte generado automáticamente');

            // Margenes
            $pdf->SetMargins(10, 15, 10);
            $pdf->SetHeaderMargin(5);
            $pdf->SetFooterMargin(10);

            // Saltos de página automáticos
            $pdf->SetAutoPageBreak(TRUE, 15);

            // Agregar página
            $pdf->AddPage();

            // Contenido HTML
            $html = '<h1 style="text-align:center;">' . $titulo . '</h1>';
            $html .= '<p style="text-align:center;">Generado el: ' . date('d/m/Y H:i:s') . '</p>';

            // Tabla de productos
            $html .= '<table border="1" cellpadding="5">';
            $html .= '<tr style="background-color:#f2f2f2;">';
            $html .= '<th><strong>Código</strong></th>';
            $html .= '<th><strong>Producto</strong></th>';
            if ($id_rol != 2) {
                $html .= '<th><strong>Vendedor</strong></th>';
            }
            $html .= '<th><strong>Categoría</strong></th>';
            $html .= '<th><strong>Stock</strong></th>';
            $html .= '<th><strong>Precio Venta</strong></th>';
            $html .= '<th><strong>Fecha Registro</strong></th>';
            $html .= '</tr>';

            foreach ($productos as $producto) {
                $html .= '<tr>';
                $html .= '<td>' . $producto['codigo'] . '</td>';
                $html .= '<td>' . $producto['nombre_producto'] . '</td>';
                if ($id_rol != 2) {
                    $html .= '<td>' . $producto['nombre_usuario'] . '</td>';
                }
                $html .= '<td>' . $producto['nombre_categoria'] . '</td>';
                $html .= '<td>' . $producto['cantidad'] . '</td>';
                $html .= '<td>$' . number_format($producto['precio_venta'], 2) . '</td>';
                $html .= '<td>' . $producto['fecha_registro']->format('d/m/Y') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table>';

            // Escribir el HTML
            $pdf->writeHTML($html, true, false, true, false, '');

            // Salida del PDF
            $pdf->Output('reporte_productos_' . date('YmdHis') . '.pdf', 'I');
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al generar PDF: " . $e->getMessage();
            header("Location: ProductoController.php");
            exit();
        }
    }
}
try {
    // Uso del controlador
    $action = $_GET['action'] ?? 'index';
    $controller = new ProductoController();

    switch ($action) {
        case 'editar':
            $controller->editar();
            break;
        case 'guardar':
            $controller->guardar();
            break;
        case 'exportar-pdf':
            $controller->exportarPDF();
            break;
        case 'cambiar-estado':
            $controller->cambiarEstado();
            break;
        case 'index':
        default:
            $controller->index();
            break;
    }
} catch (Throwable $e) { // Captura tanto Exception como Error
    // Registrar el error en logs
    error_log("Error crítico en AuthController: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());

    // Iniciar sesión si no está iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION['mensaje'] = "Error en el sistema. Por favor intente más tarde.";
    $_SESSION['alerta'] = "alert-danger";

    header('Location: ProductoController.php');
    exit();
}