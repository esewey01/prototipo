<?php
class Conexion
{
    private $server;
    private $database;
    private $user;
    private $password;
    private $connection;
    public function __construct()
    {
        // Configuración del servidor
        $this->server = "localhost";
        $this->database = "UPIICSAFOOD";
        $this->user = "david";  //USUARIO DE SQL SERVER
        $this->password = "6441";
        // Configuración de la conexión
        $connectionInfo = array(
            "Database" => $this->database,
            "UID" => $this->user,
            "PWD" => $this->password,
            "CharacterSet" => "UTF-8",
            "TrustServerCertificate" => true
        );


        //MOSTRAR ERRORES


        // Conectar a SQL Server
        $this->connection = sqlsrv_connect($this->server, $connectionInfo);

        if (!$this->connection) {
            die("Error de conexión: " . print_r(sqlsrv_errors(), true));
        } else {
            //ECHO 'Conexión exitosa a la base de datos: ';
            // echo '
            // <script language = javascript>
            //      alert(`Conexión exitosa a la base de datos:' . $this->database .'`);
            // </script>
            // ';
        }
    }



    // Métodos auxiliares para ejecutar consultas
    private function executeQuery($sql, $params = array())
    {
        $stmt = sqlsrv_query($this->connection, $sql, $params);

        if ($stmt === false) {
            die("Error en consulta: " . print_r(sqlsrv_errors(), true));
            return false;
        }

        return $stmt;
    }

    private function getResults($stmt)
    {
        $retorno = array();

        if ($stmt === false) {
            error_log("Error en consulta SQL: " . print_r(sqlsrv_errors(), true));
            return false;
        }

        while ($fila = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $retorno[] = $fila;
        }


        return $retorno;
    }

    private function executeNonQuery($sql, $params = array())
    {
        $stmt = sqlsrv_query($this->connection, $sql, $params);

        if ($stmt === false) {
            die("Error en operación: " . print_r(sqlsrv_errors(), true));
        }

        $rowsAffected = sqlsrv_rows_affected($stmt);
        sqlsrv_free_stmt($stmt);
        return $rowsAffected;
    }



    // FUNCIONES DE USUARIO


    public function getUser($login)
    {
        $sql = "SELECT * FROM usuarios WHERE login = ? OR email = ?";
        $params = array($login, $login);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }


    public function getUserRoles($id_usuario)
    {
        $sql = "SELECT r.nombre_rol FROM usuarios u
        JOIN ROLES_USUARIO ru ON u.id_usuario = ru.id_usuario
        JOIN roles r ON ru.id_rol = r.id_rol
        WHERE u.id_usuario = ? ";
        $params = array($id_usuario);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }


    public function getUserWithRole($login)
    {
        $sql = "SELECT u.*, r.id_rol, r.nombre_rol 
                FROM usuarios u
                JOIN ROLES_USUARIO ur ON u.id_usuario = ur.id_usuario
                JOIN roles r ON ur.id_rol = r.id_rol
                WHERE u.login = ?";
        $params = array($login);
        $stmt = $this->executeQuery($sql, $params);
        $result = $this->getResults($stmt);
        return !empty($result) ? $result[0] : null;
    }

    public function getMenuByRol($id_rol)
    {
        $sql = "SELECT * FROM MENU 
                WHERE (id_rol = ? OR id_rol IS NULL)
                AND estado = 'Activo'
                ORDER BY orden ASC";
        $params = array($id_rol);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    //TIPOS DE USUARIOS
    public function getUserbyType($tipo)
    {
        $sql = "SELECT * FROM usuarios u 
                JOIN tipos_usuario t ON u.id_tipo = t.id_tipo
                WHERE t.nombre_tipo = ?";
        $params = array($tipo);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }


    //FUNCION PARA COMPROBAR SI EL USUARIO EXISTE
    public function searchUser($login)
    {
        $sql = "SELECT* FROM usuarios WHERE LOGIN = ? ";
        $params = array($login);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    //MENU DE PRUEBAS
    public function getMenuMain()
    {
        $sql = "SELECT * FROM menu";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    //MENU DEL ADIMINSTRADOR
    public function getMenuAdmin()
    {
        $sql = "SELECT * FROM MenuAdmin";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }
    public function getMenuVendedor()
    {
        $sql = "SELECT * FROM MenuVendedor";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getMenuCliente()
    {
        $sql = "SELECT * FROM MenuCliente";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getMenuMainVentas()
    {
        $sql = "SELECT * FROM menu WHERE acceso = 'A'";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getAllUserData()
    {
        $sql = "SELECT * FROM usuarios";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getOnlyUserData($idUser)
    {
        $sql = "SELECT * FROM usuarios WHERE id_usu = ?";
        $params = array($idUser);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getPreventa()
    {
        $sql = "SELECT idPreventa, imagen, producto, COUNT(producto) as cantidad, 
                SUM(precio) as totalPrecio, idProducto, pventa, idUser, precio, tipo
                FROM preventa
                GROUP BY producto, idProducto, tipo, imagen, idPreventa, pventa, idUser, precio
                ORDER BY idPreventa ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getTotalPreventa()
    {
        $sql = "SELECT SUM(precio) as total, idUser FROM preventa GROUP BY idUser";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    //REGISTRAR NUEVO USUARIO

    public function RegisterNewUser($telefono, $login, $tipo, $nombre, $password, $foto)
    {
        $sql = "INSERT INTO usuarios (telefono, login, tipo, nombre, password, foto) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $params = array($telefono, $login, $tipo, $nombre, $password, $foto);
        return $this->executeNonQuery($sql, $params);
    }

    //ACTUALIZAR USUARIO
    public function getRegisterNewUser($telefono, $login, $tipo, $nombre, $password, $foto)
    {
        $sql = "UPDATE usuarios SET login = ?, tipo = ?, nombre = ?, 
                password = ?, foto = ? WHERE id_usu = ?";
        $params = array($telefono, $login, $tipo, $nombre, $password, $foto);
        return $this->executeNonQuery($sql, $params);
    }



    public function deleteUsuario($idUsuario)
    {
        $sql = "DELETE FROM usuarios WHERE id_usu = ?";
        $params = array($idUsuario);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateUsuario($login, $tipo, $nombre, $password, $foto, $idUsuario)
    {
        $sql = "UPDATE usuarios SET login = ?, tipo = ?, nombre = ?, 
                password = ?, foto = ? WHERE id_usu = ?";
        $params = array($login, $tipo, $nombre, $password, $foto, $idUsuario);
        return $this->executeNonQuery($sql, $params);
    }

    public function getMensajeAlerta()
    {
        $sql = "SELECT * FROM alerta";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function updateMensajeAlert($mensaje, $alerta)
    {
        $sql = "UPDATE alerta SET tipoAlerta = ?, mensaje = ? WHERE alertaId = 1";
        $params = array($alerta, $mensaje);
        return $this->executeNonQuery($sql, $params);
    }

    public function getDataFactura()
    {
        $sql = "SELECT * FROM datos";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function updateDataFactura($propietario, $razon, $direccion, $nro, $telefono)
    {
        $sql = "UPDATE datos SET propietario = ?, razon = ?, direccion = ?, 
                nro = ?, telefono = ? WHERE iddatos = 1";
        $params = array($propietario, $razon, $direccion, $nro, $telefono);
        return $this->executeNonQuery($sql, $params);
    }

    public function getMoneda()
    {
        $sql = "SELECT * FROM moneda";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function updateDataMoneda($idMoneda, $pais, $tipoMoneda, $contexto)
    {
        $sql = "UPDATE moneda SET pais = ?, tipoMoneda = ?, contexto = ? 
                WHERE idMoneda = ?";
        $params = array($pais, $tipoMoneda, $contexto, $idMoneda);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateDataIdioma($idioma, $idIdioma)
    {
        $sql = "UPDATE idioma SET idioma = ? WHERE idIdioma = ?";
        $params = array($idioma, $idIdioma);
        return $this->executeNonQuery($sql, $params);
    }

    public function getIdioma()
    {
        $sql = "SELECT * FROM idioma";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function updateIdiomaSistem($opcionMenu, $idIdioma)
    {
        $sql = "UPDATE menu SET opcion = ? WHERE idmenu = ?";
        $params = array($opcionMenu, $idIdioma);
        return $this->executeNonQuery($sql, $params);
    }

    public function getAllProveedor()
    {
        $sql = "SELECT * FROM proveedor";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registerNewProveedor($proveedor, $responsable, $direccion, $telefono, $fechaRegistro)
    {
        $sql = "INSERT INTO proveedor (proveedor, responsable, fechaRegistro, direccion, telefono) 
                VALUES (?, ?, ?, ?, ?)";
        $params = array($proveedor, $responsable, $fechaRegistro, $direccion, $telefono);
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteProveedor($idProveedor)
    {
        $sql = "DELETE FROM proveedor WHERE idproveedor = ?";
        $params = array($idProveedor);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateProveedor($idProveedor, $proveedor, $responsable, $direccion, $telefono, $fechaRegistro)
    {
        $sql = "UPDATE proveedor SET proveedor = ?, responsable = ?, direccion = ?, 
                telefono = ?, fechaRegistro = ? WHERE idproveedor = ?";
        $params = array($proveedor, $responsable, $direccion, $telefono, $fechaRegistro, $idProveedor);
        return $this->executeNonQuery($sql, $params);
    }

    public function getAllCliente()
    {
        $sql = "SELECT * FROM cliente";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registerNewCliente($imagen, $nombre, $apellido, $direccion, $telefonoFijo, $telefonoCelular, $email, $fechaRegistro, $ci)
    {
        $sql = "INSERT INTO cliente (foto, nombre, apellido, direccion, telefonoFijo, 
                telefonoCelular, email, fechaRegistro, ci) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array(
            $imagen,
            $nombre,
            $apellido,
            $direccion,
            $telefonoFijo,
            $telefonoCelular,
            $email,
            $fechaRegistro,
            $ci
        );
        return $this->executeNonQuery($sql, $params);
    }

    public function updateClient($idcliente, $imagen, $nombre, $apellido, $direccion, $telefonoFijo, $telefonoCelular, $email, $fechaRegistro, $ci)
    {
        $sql = "UPDATE cliente SET foto = ?, nombre = ?, apellido = ?, direccion = ?, 
                telefonoFijo = ?, telefonoCelular = ?, email = ?, fechaRegistro = ?, ci = ? 
                WHERE idcliente = ?";
        $params = array(
            $imagen,
            $nombre,
            $apellido,
            $direccion,
            $telefonoFijo,
            $telefonoCelular,
            $email,
            $fechaRegistro,
            $ci,
            $idcliente
        );
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteClient($idClient)
    {
        $sql = "DELETE FROM cliente WHERE idcliente = ?";
        $params = array($idClient);
        return $this->executeNonQuery($sql, $params);
    }

    public function getTipoMoneda()
    {
        $sql = "SELECT * FROM moneda";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getProductoElegido($idproducto)
    {
        $sql = "SELECT * FROM producto WHERE idproducto = ?";
        $params = array($idproducto);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function insertarPreventaProducto($imagen, $producto, $precio, $idProducto, $pventa, $idUser, $tipo)
    {
        $sql = "INSERT INTO preventa (imagen, producto, precio, idProducto, pventa, idUser, tipo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = array($imagen, $producto, $precio, $idProducto, $pventa, $idUser, $tipo);
        return $this->executeNonQuery($sql, $params);
    }

    public function getAllProducto()
    {
        $sql = "SELECT * FROM producto";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getAllTipoProducto()
    {
        $sql = "SELECT * FROM tipoproducto";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registerNewProducto($imagen, $codigo, $nombreProducto, $cantidad, $fechaRegistro, $precioVenta, $tipo, $proveedor, $precioCompra)
    {
        $sql = "INSERT INTO producto (imagen, codigo, nombreProducto, cantidad, 
                fechaRegistro, precioVenta, tipo, proveedor, precioCompra) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array(
            $imagen,
            $codigo,
            $nombreProducto,
            $cantidad,
            $fechaRegistro,
            $precioVenta,
            $tipo,
            $proveedor,
            $precioCompra
        );
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteProduct($idproducto)
    {
        $sql = "DELETE FROM producto WHERE idproducto = ?";
        $params = array($idproducto);
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteOnlyPreventa($idProducto, $tipo)
    {
        $sql = "DELETE FROM preventa WHERE idproducto = ? AND tipo = ?";
        $params = array($idProducto, $tipo);
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteAllPreventa()
    {
        $sql = "TRUNCATE TABLE preventa";
        return $this->executeNonQuery($sql);
    }

    public function updateProduct($imagen, $codigo, $nombreProducto, $cantidad, $fechaRegistro, $precioVenta, $tipo, $proveedor, $precioCompra, $idproducto)
    {
        $sql = "UPDATE producto SET imagen = ?, codigo = ?, nombreProducto = ?, 
                cantidad = ?, fechaRegistro = ?, precioVenta = ?, tipo = ?, 
                proveedor = ?, precioCompra = ? WHERE idproducto = ?";
        $params = array(
            $imagen,
            $codigo,
            $nombreProducto,
            $cantidad,
            $fechaRegistro,
            $precioVenta,
            $tipo,
            $proveedor,
            $precioCompra,
            $idproducto
        );
        return $this->executeNonQuery($sql, $params);
    }

    public function registerNewTipoProduct($tipoProducto)
    {
        $sql = "INSERT INTO tipoproducto (tipoproducto) VALUES (?)";
        $params = array($tipoProducto);
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteTipoProduct($tipoProductoId)
    {
        $sql = "DELETE FROM tipoproducto WHERE idtipoproducto = ?";
        $params = array($tipoProductoId);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateTipoProducto($tipoProductoId, $tipoproducto)
    {
        $sql = "UPDATE tipoproducto SET tipoproducto = ? WHERE idtipoproducto = ?";
        $params = array($tipoproducto, $tipoProductoId);
        return $this->executeNonQuery($sql, $params);
    }

    public function getAllActivos()
    {
        $sql = "SELECT * FROM activos";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registerNewActivo($imagen, $codigo, $nombreProducto, $cantidad, $fechaRegistro)
    {
        $sql = "INSERT INTO activos (imagen, codigo, nombreProducto, cantidad, fechaRegistro) 
                VALUES (?, ?, ?, ?, ?)";
        $params = array($imagen, $codigo, $nombreProducto, $cantidad, $fechaRegistro);
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteActivo($idproducto)
    {
        $sql = "DELETE FROM activos WHERE idactivo = ?";
        $params = array($idproducto);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateActivo($imagen, $codigo, $nombreProducto, $cantidad, $fechaRegistro, $idproducto)
    {
        $sql = "UPDATE activos SET imagen = ?, codigo = ?, nombreProducto = ?, 
                cantidad = ?, fechaRegistro = ? WHERE idactivo = ?";
        $params = array($imagen, $codigo, $nombreProducto, $cantidad, $fechaRegistro, $idproducto);
        return $this->executeNonQuery($sql, $params);
    }

    public function getDataProductoChoose($idProducto, $tipo)
    {
        $sql = "SELECT * FROM preventa WHERE idproducto = ? AND tipo = ?";
        $params = array($idProducto, $tipo);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getCantidadProductoChoose($idProducto, $tipo)
    {
        $sql = "SELECT COUNT(idproducto) as cantidadTotal FROM preventa WHERE idproducto = ? AND tipo = ?";
        $params = array($idProducto, $tipo);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getContact($nitClient)
    {
        $sql = "SELECT * FROM cliente WHERE ci = ?";
        $params = array($nitClient);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getDatosFactura()
    {
        $sql = "SELECT * FROM datos";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getDatosDosificacion()
    {
        $sql = "SELECT * FROM dosificacion";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registrarDatosPreventa($ci, $nombre, $totalAPagar, $efectivo, $cambio, $fechaVenta, $idcliente)
    {
        $sql = "INSERT INTO clientedato (nombre, ci, fecha, totalApagar, efectivo, cambio, idClientei, tipoVenta) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Local')";
        $params = array($nombre, $ci, $fechaVenta, $totalAPagar, $efectivo, $cambio, $idcliente);
        return $this->executeNonQuery($sql, $params);
    }

    public function getDataCliente()
    {
        $sql = "SELECT TOP 1 * FROM clientedato ORDER BY idCliente DESC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getClienteDatos($nitClient)
    {
        $sql = "SELECT * FROM cliente WHERE ci = ?";
        $params = array($nitClient);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getPedidoTotalForFactura()
    {
        $sql = "SELECT idpreventa, imagen, producto, precio, COUNT(idproducto) AS cantidad, 
                precio*COUNT(idproducto) as totalPrecio, idproducto, pventa, tipo 
                FROM preventa GROUP BY idproducto, idpreventa, imagen, producto, precio, pventa, tipo";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getNumFicha($dateInicial, $dateFinal)
    {
        $sql = "SELECT (COUNT(*) + 1) as numficha FROM ventatotal 
                WHERE fecha >= ? AND fecha <= ?";
        $params = array($dateInicial . ' 00:00:00', $dateFinal . ' 23:59:00');
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function registrarVenta($nombre, $ci, $totalAPagar, $efectivo, $cambio, $idClientei, $codigoControl, $fechaVenta)
    {
        $sql = "INSERT INTO ventatotal (nombre, ci, fecha, totalApagar, efectivo, cambio, idClientei, codigoControl) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($nombre, $ci, $fechaVenta, $totalAPagar, $efectivo, $cambio, $idClientei, $codigoControl);
        return $this->executeNonQuery($sql, $params);
    }

    public function getDatosVenta()
    {
        $sql = "SELECT * FROM ventatotal";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registrarDatosVenta($cantidad, $descripcion, $precio, $total, $tipo, $fechaVenta, $codigoControl, $idVentas, $estado)
    {
        $sql = "INSERT INTO datosventa (cantidad, descripcion, precio, total, tipo, fechaVenta, codigoControl, idVentas, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($cantidad, $descripcion, $precio, $total, $tipo, $fechaVenta, $codigoControl, $idVentas, $estado);
        return $this->executeNonQuery($sql, $params);
    }

    public function registrarDatosVentaTotal($cliente, $cantidad, $precio, $total, $codigoControl, $fechaVenta, $estado, $comentario)
    {
        $sql = "INSERT INTO datosventatotal (cliente, cantidad, precio, total, codigoControl, fechaVenta, estado, comentario) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($cliente, $cantidad, $precio, $total, $codigoControl, $fechaVenta, $estado, $comentario);
        return $this->executeNonQuery($sql, $params);
    }

    public function registrarDatosClienteVenta($fechaVenta, $nitci, $cliente, $codigoControl, $idVentas, $estado)
    {
        $sql = "INSERT INTO datosclienteventa (fechaVenta, nitCliente, cliente, codigoControl, idVentas, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = array($fechaVenta, $nitci, $cliente, $codigoControl, $idVentas, $estado);
        return $this->executeNonQuery($sql, $params);
    }

    public function registrarDatosFacturaVenta($nit, $factura, $numeroAutorizacion, $codigoControl, $idVentas, $estado)
    {
        $sql = "INSERT INTO datosfacturaventa (nit, factura, numeroAutorizacion, codigoControl, idVentas, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = array($nit, $factura, $numeroAutorizacion, $codigoControl, $idVentas, $estado);
        return $this->executeNonQuery($sql, $params);
    }

    public function cleanRegistroPreventa()
    {
        $sql = "TRUNCATE TABLE preventa";
        return $this->executeNonQuery($sql);
    }

    public function cleanClientData()
    {
        $sql = "TRUNCATE TABLE clientedato";
        return $this->executeNonQuery($sql);
    }

    public function getAllGastos()
    {
        $sql = "SELECT * FROM gastos ORDER BY idgastos DESC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registerNewAccount($tipo, $descripcion, $entrada, $fechaRegistro, $usuario, $salida)
    {
        $sql = "INSERT INTO gastos (descripcion, entrada, usuario, salida, tipo, fechaRegistro) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = array($descripcion, $entrada, $usuario, $salida, $tipo, $fechaRegistro);
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteAccount($idCuenta)
    {
        $sql = "DELETE FROM gastos WHERE idgastos = ?";
        $params = array($idCuenta);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateAccount($tipo, $descripcion, $entrada, $fechaRegistro, $usuario, $salida, $idCuenta)
    {
        $sql = "UPDATE gastos SET descripcion = ?, entrada = ?, fechaRegistro = ?, 
                usuario = ?, salida = ?, tipo = ? WHERE idgastos = ?";
        $params = array($descripcion, $entrada, $fechaRegistro, $usuario, $salida, $tipo, $idCuenta);
        return $this->executeNonQuery($sql, $params);
    }

    public function getAllPedido()
    {
        $sql = "SELECT * FROM pedido ORDER BY idPedido DESC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function registerNewPedido($descripcion, $total, $empresa, $usuario, $fechaRegistro)
    {
        $sql = "INSERT INTO pedido (descripcion, total, proveedor, usuario, fechaRegistro) 
                VALUES (?, ?, ?, ?, ?)";
        $params = array($descripcion, $total, $empresa, $usuario, $fechaRegistro);
        return $this->executeNonQuery($sql, $params);
    }

    public function deletePedido($idPedido)
    {
        $sql = "DELETE FROM pedido WHERE idPedido = ?";
        $params = array($idPedido);
        return $this->executeNonQuery($sql, $params);
    }

    public function updatePedido($descripcion, $total, $proveedor, $usuarioLogin, $fechaRegistro, $idPedido)
    {
        $sql = "UPDATE pedido SET descripcion = ?, total = ?, proveedor = ?, 
                usuario = ?, fechaRegistro = ? WHERE idPedido = ?";
        $params = array($descripcion, $total, $proveedor, $usuarioLogin, $fechaRegistro, $idPedido);
        return $this->executeNonQuery($sql, $params);
    }

    public function insertarComentarioFicha($idVenta, $comentario)
    {
        $sql = "UPDATE datosventatotal SET comentario = ? WHERE idVentas = ?";
        $params = array($comentario, $idVenta);
        return $this->executeNonQuery($sql, $params);
    }

    public function getAllVentas()
    {
        $sql = "SELECT * FROM datosventatotal WHERE estado = 'NoConsolidado' ORDER BY idVentas ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function updateDatosclienteventa($codigoControl)
    {
        $sql = "UPDATE datosclienteventa SET estado = 'Consolidado' WHERE codigoControl = ?";
        $params = array($codigoControl);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateDatosfacturaventa($codigoControl)
    {
        $sql = "UPDATE datosfacturaventa SET estado = 'Consolidado' WHERE codigoControl = ?";
        $params = array($codigoControl);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateDatosventa($codigoControl)
    {
        $sql = "UPDATE datosventa SET estado = 'Consolidado' WHERE codigoControl = ?";
        $params = array($codigoControl);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateDatosventatotal($codigoControl)
    {
        $sql = "UPDATE datosventatotal SET estado = 'Consolidado' WHERE codigoControl = ?";
        $params = array($codigoControl);
        return $this->executeNonQuery($sql, $params);
    }

    public function getVentasDia($fechaInicial, $fechaFinal)
    {
        $sql = "SELECT * FROM datosventatotal 
                WHERE fechaVenta >= ? AND fechaVenta < ? AND estado = 'Consolidado'";
        $params = array($fechaInicial, $fechaFinal);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getVentasTotalesDia($fechaInicial, $fechaFinal)
    {
        $sql = "SELECT SUM(total) as totalVentas FROM datosventatotal 
                WHERE fechaVenta >= ? AND fechaVenta < ? AND estado = 'Consolidado'";
        $params = array($fechaInicial, $fechaFinal);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getVentasProductoByDia($fechaInicial, $fechaFinal)
    {
        $sql = "SELECT * FROM datosventa 
                WHERE fechaVenta >= ? AND fechaVenta < ? AND estado = 'Consolidado'";
        $params = array($fechaInicial, $fechaFinal);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getVentasProductoTotalesDia($fechaInicial, $fechaFinal)
    {
        $sql = "SELECT SUM(total) as totalVentas FROM datosventa 
                WHERE fechaVenta >= ? AND fechaVenta < ? AND estado = 'Consolidado'";
        $params = array($fechaInicial, $fechaFinal);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getVentasMensuales()
    {
        $sql = "SELECT DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getSumaTotalVentasByMes($mes, $anio)
    {
        $sql = "SELECT SUM(cantidad * precio) as totalVentas 
                FROM datosventatotal 
                WHERE MONTH(fechaVenta) = ? AND YEAR(fechaVenta) = ?";
        $params = array($mes, $anio);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getTotalVentasByMes($mes, $anio)
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DAY(fechaVenta) as dia 
                FROM datosventatotal 
                WHERE MONTH(fechaVenta) = ? AND YEAR(fechaVenta) = ? 
                GROUP BY DAY(fechaVenta) 
                ORDER BY DAY(fechaVenta) ASC";
        $params = array($mes, $anio);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getTotalVentasByYear($anio)
    {
        $sql = "SELECT SUM(cantidad * precio) as totalVentas 
                FROM datosventatotal 
                WHERE YEAR(fechaVenta) = ?";
        $params = array($anio);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getTotalVentasByAnio($anio)
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                WHERE YEAR(fechaVenta) = ? 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $params = array($anio);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getTotalVentas6Meses()
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                WHERE fechaVenta BETWEEN DATEADD(MONTH, -6, GETDATE()) AND GETDATE() 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getGrandTotalVentas6Meses()
    {
        $sql = "SELECT SUM(cantidad * precio) as totalVentas 
                FROM datosventatotal 
                WHERE fechaVenta BETWEEN DATEADD(MONTH, -6, GETDATE()) AND GETDATE()";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getGatosDeLaEmpresa($fechaVentasI, $fechaVentasF)
    {
        $sql = "SELECT * FROM gastos 
                WHERE fechaRegistro BETWEEN ? AND ?";
        $params = array($fechaVentasI, $fechaVentasF);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getEntradasDeLaEmpresa($fechaVentasI, $fechaVentasF)
    {
        $sql = "SELECT SUM(entrada) as totalEntrada 
                FROM gastos 
                WHERE fechaRegistro BETWEEN ? AND ?";
        $params = array($fechaVentasI, $fechaVentasF);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getTotalGatosDeLaEmpresa($fechaVentasI, $fechaVentasF)
    {
        $sql = "SELECT SUM(salida) as totalSalida 
                FROM gastos 
                WHERE fechaRegistro BETWEEN ? AND ?";
        $params = array($fechaVentasI, $fechaVentasF);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getUtilidadDeLaEmpresa($fechaVentasI, $fechaVentasF)
    {
        $sql = "SELECT (SUM(entrada) - SUM(salida)) as utilidad 
                FROM gastos 
                WHERE fechaRegistro BETWEEN ? AND ?";
        $params = array($fechaVentasI, $fechaVentasF);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getSumTotalVentasMensuales()
    {
        $sql = "SELECT DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getTotalVentasMensual()
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                WHERE estado = 'Consolidado' 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getTotalVentas()
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                WHERE estado = 'Consolidado' 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getTotalByMonthVentas($mes, $anio)
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DAY(fechaVenta) as dia 
                FROM datosventatotal 
                WHERE estado = 'Consolidado' AND MONTH(fechaVenta) = ? AND YEAR(fechaVenta) = ? 
                GROUP BY DAY(fechaVenta) 
                ORDER BY DAY(fechaVenta) ASC";
        $params = array($mes, $anio);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getTotal6MonthVentas()
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                WHERE estado = 'Consolidado' AND fechaVenta BETWEEN DATEADD(MONTH, -6, GETDATE()) AND GETDATE() 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getTotalYearVentas($anio)
    {
        $sql = "SELECT SUM(cantidad * precio) as total, DATENAME(MONTH, fechaVenta) as mes 
                FROM datosventatotal 
                WHERE estado = 'Consolidado' AND YEAR(fechaVenta) = ? 
                GROUP BY MONTH(fechaVenta) 
                ORDER BY MONTH(fechaVenta) ASC";
        $params = array($anio);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function updateOpcionElegida($colorElegido, $idMenu)
    {
        $sql = "UPDATE menu SET color = ? WHERE idmenu = ?";
        $params = array($colorElegido, $idMenu);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateOpcionDefecto($colorDefecto, $idMenu)
    {
        $sql = "UPDATE menu SET color = ? WHERE idmenu != ?";
        $params = array($colorDefecto, $idMenu);
        return $this->executeNonQuery($sql, $params);
    }



    //FUNCIONES PARA USUARIOS "CLIENTES"

    public function NewRegisterUsuario($nombre, $tipo, $usuario, $passwordHash, $foto)
    {
        $sql = "INSERT INTO usuarios (login, tipo, nombre, password, foto) 
                VALUES (?, ?, ?, ?, ?)";
        $params = array($usuario, $tipo, $nombre, $passwordHash, $foto);
        return $this->executeNonQuery($sql, $params);
    }

    // En Conexion.php, añade estos nuevos métodos:

    public function updateUserProfile($id_usuario, $nombre, $telefono, $foto = null)
    {
        if ($foto) {
            $sql = "UPDATE usuarios SET nombre = ?, telefono = ?, foto = ? WHERE id_usuario = ?";
            $params = array($nombre, $telefono, $foto, $id_usuario);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, telefono = ? WHERE id_usuario = ?";
            $params = array($nombre, $telefono, $id_usuario);
        }
        return $this->executeNonQuery($sql, $params);
    }

    public function updatePassword($id_usuario, $newPassword)
    {
        $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
        $params = array($newPassword, $id_usuario);
        return $this->executeNonQuery($sql, $params);
    }

    public function getSocialNetworks($id_usuario)
    {
        $sql = "SELECT * FROM redes_sociales WHERE id_usuario = ?";
        $params = array($id_usuario);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function updateSocialNetworks($id_usuario, $facebook, $instagram, $twitter)
    {
        // Primero verifica si ya existe
        $existing = $this->getSocialNetworks($id_usuario);

        if (empty($existing)) {
            $sql = "INSERT INTO redes_sociales (id_usuario, facebook, instagram, twitter) VALUES (?, ?, ?, ?)";
        } else {
            $sql = "UPDATE redes_sociales SET facebook = ?, instagram = ?, twitter = ? WHERE id_usuario = ?";
        }

        $params = empty($existing) ?
            array($id_usuario, $facebook, $instagram, $twitter) :
            array($facebook, $instagram, $twitter, $id_usuario);

        return $this->executeNonQuery($sql, $params);
    }

    public function createSellerRequest($id_usuario, $id_categoria, $descripcion)
    {
        $sql = "INSERT INTO solicitudes_vendedor (id_usuario, id_categoria, descripcion) VALUES (?, ?, ?)";
        $params = array($id_usuario, $id_categoria, $descripcion);
        return $this->executeNonQuery($sql, $params);
    }

    public function getCategories()
    {
        $sql = "SELECT * FROM categorias WHERE estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }


    /////////////////////////////////METODOS PARA SOLICITUDES
    // Obtener todas las solicitudes de vendedores
    public function getSolicitudesVendedores()
    {
        $sql = "SELECT 
                    sv.id_solicitud,
                    sv.descripcion,
                    CONVERT(VARCHAR, sv.fecha_solicitud, 103) as fecha_formateada,
                    sv.estado,
                    u.nombre, 
                    u.login, 
                    c.nombre as categoria 
                FROM solicitudes_vendedor sv
                JOIN usuarios u ON sv.id_usuario = u.id_usuario
                JOIN categorias c ON sv.id_categoria = c.id_categoria
                ORDER BY sv.fecha_solicitud DESC";

        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function formatDate($date)
    {
        if ($date instanceof DateTime) {
            return $date->format('d/m/Y');
        }
        return date('d/m/Y', strtotime($date));
    }
    // Obtener solicitudes de un usuario específico
    public function getSolicitudesUsuario($id_usuario)
    {
        $sql = "SELECT sv.*, c.nombre as categoria 
            FROM solicitudes_vendedor sv
            JOIN categorias c ON sv.id_categoria = c.id_categoria
            WHERE sv.id_usuario = ?
            ORDER BY sv.fecha_solicitud DESC";
        $params = array($id_usuario);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    // Actualizar estado de solicitud
    public function actualizarEstadoSolicitud($id_solicitud, $estado)
    {
        $sql = "UPDATE solicitudes_vendedor SET estado = ? WHERE id_solicitud = ?";
        $params = array($estado, $id_solicitud);
        return $this->executeNonQuery($sql, $params);
    }


    // Versión con tipo de producto
    public function getProductosByVendedor($id_vendedor)
    {
        $sql = "SELECT 
                p.id_producto,
                p.codigo,
                p.nombre_producto,
                p.descripcion,
                p.cantidad,
                p.precio_compra,
                p.precio_venta,
                p.imagen,
                CONVERT(VARCHAR, p.fecha_registro, 103) as fecha_registro,
                p.estado,
                pr.nombre as nombre_proveedor,
                tp.nombre as tipo_producto
            FROM PRODUCTOS p
            JOIN PROVEEDOR pr ON p.id_proveedor = pr.idproveedor
            JOIN TIPOPRODUCTO tp ON p.id_tipo_producto = tp.id_tipo
            WHERE p.id_vendedor = ?
            ORDER BY p.fecha_registro DESC";

        $params = array($id_vendedor);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }
    public function insertProducto($datos)
    {
        $sql = "INSERT INTO PRODUCTOS (
                    id_vendedor, 
                    id_proveedor,
                    codigo,
                    nombre_producto,
                    descripcion,
                    cantidad,
                    precio_compra,
                    precio_venta,
                    imagen,
                    id_tipo_producto
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $datos['id_vendedor'],
            $datos['id_proveedor'],
            $datos['codigo'],
            $datos['nombre_producto'],
            $datos['descripcion'],
            $datos['cantidad'],
            $datos['precio_compra'],
            $datos['precio_venta'],
            $datos['imagen'] ?? 'default.jpg',
            $datos['id_tipo_producto']
        ];

        return $this->executeNonQuery($sql, $params);
    }
}
