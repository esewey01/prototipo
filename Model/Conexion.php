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



    //EJECUTAR SELECT 
    private function executeQuery($sql, $params = array())
    {
        $stmt = sqlsrv_query($this->connection, $sql, $params);

        if ($stmt === false) {
            die("Error en consulta: " . print_r(sqlsrv_errors(), true));
            return false;
        }

        return $stmt;
    }


    //CONVIERTE UN RESULTADO EN UN ARREGLO
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

    public function getRow($sql, $params = [])
    {
        $stmt = sqlsrv_query($this->connection, $sql, $params);
        if ($stmt === false) {
            // Manejar el error de la consulta
            return false; // O lanzar una excepción
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($stmt);
        return $row;
    }

    //PARA EJECUTAR UN INSERT/UPDATE O DELETE
    private function executeNonQuery($sql, $params = array())
    {
        $stmt = sqlsrv_query($this->connection, $sql, $params);

        if ($stmt === false) {
            $errors = sqlsrv_errors();
            error_log("Error en consulta SQL: " . print_r($errors, true));
            return false;
        }

        return true;
    }



    //FUNCION PARA OBTENER EL ULTIMO ID
    public function getLastInsertId()
    {
        $sql = "SELECT SCOPE_IDENTITY() AS last_id";
        $stmt = $this->executeQuery($sql);
        $result = $this->getResults($stmt);
        return $result[0]['last_id'];
    }


    public function getUser($login)
    {
        $sql = "SELECT * FROM USUARIOS WHERE login = ?";
        $stmt = $this->executeQuery($sql, array($login));
        $result = $this->getResults($stmt);
        return $result ?? null;
    }

    // FUNCIONES PARA LOS UUSARIOS 
    public function searchUser($login)
    {
        $sql = "SELECT id_usuario FROM USUARIOS WHERE login = ?";
        $stmt = $this->executeQuery($sql, [$login]);
        $result = $this->getResults($stmt);
        return !empty($result);
    }
    //REGISTRAR NUEVO USUARIO
    public function registerUserWithRole($nombre, $login, $password, $foto_perfil, $telefono, $id_rol)
    {
        // Iniciar transacción para asegurar integridad
        sqlsrv_begin_transaction($this->connection);

        try {
            // 1. Insertar el usuario y obtener el ID insertado directamente
            $sql_user = "INSERT INTO USUARIOS 
                    (nombre, login, password, foto_perfil, telefono, fecha_registro) 
                    OUTPUT INSERTED.id_usuario
                    VALUES (?, ?, ?, ?, ?, GETDATE())";
            $result = $this->executeQuery($sql_user, array($nombre, $login, $password, $foto_perfil, $telefono));

            // Obtener el ID del nuevo usuario desde el resultado
            $row = $this->getResults($result);
            if (!$row || !isset($row[0]['id_usuario'])) {
                throw new Exception("No se pudo obtener el ID del nuevo usuario.");
            }


            $id_usuario = $row[0]['id_usuario'];

            // 2. Asignar el rol
            $sql_role = "INSERT INTO ROLES_USUARIO (id_usuario, id_rol) VALUES (?, ?)";
            $this->executeNonQuery($sql_role, array($id_usuario, $id_rol));

            // Confirmar transacción si todo fue bien
            sqlsrv_commit($this->connection);
            return true;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            sqlsrv_rollback($this->connection);
            throw $e; // Relanzar la excepción para manejarla en el controlador
        }
    }


    //FUNCION PARA OBTENER EL ROL DEL USUARIO
    public function getRolUser($id_usuario)
    {
        $sql = "SELECT r.id_rol, r.nombre_rol 
                FROM ROLES_USUARIO ru
                JOIN ROLES r ON ru.id_rol = r.id_rol
                WHERE ru.id_usuario = ?";
        $stmt = $this->executeQuery($sql, array($id_usuario));
        $result = $this->getResults($stmt);
        return $result[0] ?? null;
    }

    //FUNCION PARA HABILITAR LA VERIFICACION DEL USUARIO
    public function verificarUsuario($id_usuario, $verificado)
    {
        $sql = "UPDATE USUARIOS SET verificado = ? WHERE id_usuario = ?";
        $respuesta = $this->executeNonQuery($sql, array($verificado, $id_usuario));
        return $this->getResults($respuesta);
    }


    //FUNCIONES PARA OBTENER EL ROL JUNTO CON EL USUARIO *TOODS LOSD DATOS
    public function getUserWithRole($login)
    {
        $sql = "SELECT u.*, r.nombre_rol, r.id_rol 
            FROM USUARIOS u
            JOIN ROLES_USUARIO ur ON u.id_usuario = ur.id_usuario
            JOIN ROLES r ON ur.id_rol = r.id_rol
            WHERE u.login = ?";
        $stmt = $this->executeQuery($sql, array($login));
        $result = $this->getResults($stmt);
        return $result[0] ?? null;
    }

    //FUNCION PARA OBTENER A TODOS LOS USUARIOS CON SU ROL
    // Obtener todos los usuarios con sus roles
    public function getAllUsersWithRoles()
    {
        $sql = "SELECT u.*, r.nombre_rol 
            FROM USUARIOS u
            JOIN ROLES_USUARIO ru ON u.id_usuario = ru.id_usuario
            JOIN ROLES r ON ru.id_rol = r.id_rol
            ORDER BY r.nombre_rol, u.nombre";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    // Obtener usuarios por tipo de rol
    public function getUsersByRoleType($roleType)
    {
        $sql = "SELECT u.*, r.nombre_rol as tipo 
            FROM USUARIOS u
            JOIN ROLES_USUARIO ru ON u.id_usuario = ru.id_usuario
            JOIN ROLES r ON ru.id_rol = r.id_rol
            WHERE r.nombre_rol = ?
            ORDER BY u.nombre";
        $stmt = $this->executeQuery($sql, array($roleType));
        return $this->getResults($stmt);
    }

    // Verificar si el usuario actual es super usuario
    public function isSuperUser($userId)
    {
        $sql = "SELECT COUNT(*) as is_super 
            FROM ROLES_USUARIO ru
            JOIN ROLES r ON ru.id_rol = r.id_rol
            WHERE ru.id_usuario = ? AND r.nombre_rol = 'SUPER_USER'";
        $stmt = $this->executeQuery($sql, array($userId));
        $result = $this->getResults($stmt);
        return ($result[0]['is_super'] > 0);
    }

    //OBTENER ROLES 
    public function getRoles()
    {
        $sql = "SELECT id_rol, nombre_rol from ROLES ";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }



    public function getMenuByRol($id_rol)
    {
        $sql = "SELECT * FROM MENU 
            WHERE id_rol = ? OR id_rol IS NULL
            ORDER BY orden";
        $stmt = $this->executeQuery($sql, array($id_rol));
        return $this->getResults($stmt);
    }


    //CCREAR NUEVO USUARIO
    public function createUserWithRole($nombre, $login, $password, $foto_perfil, $email, $id_rol)
    {
        // Iniciar transacción para asegurar integridad
        sqlsrv_begin_transaction($this->connection);

        try {
            // 1. Insertar el usuario y obtener el ID insertado directamente
            $sql_user = "INSERT INTO USUARIOS 
                    (nombre, login, password, foto_perfil, email, fecha_registro) 
                    OUTPUT INSERTED.id_usuario
                    VALUES (?, ?, ?, ?, ?, GETDATE())";
            $result = $this->executeQuery($sql_user, array($nombre, $login, $password, $foto_perfil, $email));

            // Obtener el ID del nuevo usuario desde el resultado
            $row = $this->getResults($result);
            if (!$row || !isset($row[0]['id_usuario'])) {
                throw new Exception("No se pudo obtener el ID del nuevo usuario.");
            }


            $id_usuario = $row[0]['id_usuario'];

            // 2. Asignar el rol
            $sql_role = "INSERT INTO ROLES_USUARIO (id_usuario, id_rol) VALUES (?, ?)";
            $this->executeNonQuery($sql_role, array($id_usuario, $id_rol));

            // Confirmar transacción si todo fue bien
            sqlsrv_commit($this->connection);
            return $id_usuario;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            sqlsrv_rollback($this->connection);
            throw $e; // Relanzar la excepción para manejarla en el controlador
        }
    }




    // ACTUALIZAR PERFIL
    public function updateUserProfile(
        $id_usuario,
        $email,
        $nombre,
        $apellido,
        $telefono,
        $direccion,
        $fecha_nacimiento,
        $genero,
        $foto_perfil
    ) {
        $sql = "UPDATE USUARIOS SET 
                    email = ?,
                    nombre = ?,
                    apellido = ?,
                    telefono = ?,
                    direccion = ?,
                    fecha_nacimiento = ?,
                    genero = ?,
                    foto_perfil = ?
                WHERE id_usuario = ?";

        $params = array(
            $email,
            $nombre,
            $apellido,
            $telefono,
            $direccion,
            $fecha_nacimiento,
            $genero,
            $foto_perfil,
            $id_usuario
        );

        $result = $this->executeNonQuery($sql, $params);
        return $result;
    }

    public function updatePassword($id_usuario, $new_password)
    {
        $sql = "UPDATE USUARIOS SET password = ? WHERE id_usuario = ?";
        return $this->executeNonQuery($sql, array($id_usuario, $new_password));
    }

    public function updateSocialNetworks($id_usuario, $facebook, $instagram, $linkedin, $twitter)
    {
        // Primero verifica si ya existe registro
        $check = "SELECT * FROM REDES_SOCIALES WHERE id_usuario = ?";
        $exists = $this->getResults($this->executeQuery($check, array($id_usuario)));

        if ($exists) {
            $sql = "UPDATE REDES_SOCIALES SET url_perfil = ? WHERE id_usuario = ? AND tipo_red = ?";
            $this->executeNonQuery($sql, array($facebook, $id_usuario, 'facebook'));
            $this->executeNonQuery($sql, array($instagram, $id_usuario, 'instagram'));
            $this->executeNonQuery($sql, array($linkedin, $id_usuario, 'linkedin'));
            $this->executeNonQuery($sql, array($twitter, $id_usuario, 'twitter'));
        } else {
            $sql = "INSERT INTO REDES_SOCIALES (id_usuario, tipo_red, url_perfil) VALUES (?, ?, ?)";
            $this->executeNonQuery($sql, array($id_usuario, 'facebook', $facebook));
            $this->executeNonQuery($sql, array($id_usuario, 'instagram', $instagram));
            $this->executeNonQuery($sql, array($id_usuario, 'linkedin', $linkedin));
            $this->executeNonQuery($sql, array($id_usuario, 'twitter', $twitter));
        }
    }

    public function createSellerRequest($id_usuario, $id_categoria, $descripcion)
    {
        $sql = "INSERT INTO SOLICITUDES_VENDEDOR (id_usuario, id_categoria, descripcion) 
            VALUES (?, ?, ?)";
        $params = array($id_usuario, $id_categoria, $descripcion);
        return $this->executeNonQuery($sql, $params);
    }

    public function getSolicitudesUsuario($id_usuario)
    {
        $sql = "SELECT sv.*, c.nombre_categoria as categoria 
            FROM SOLICITUDES_VENDEDOR sv
            JOIN CATEGORIAS c ON sv.id_categoria = c.id_categoria
            WHERE sv.id_usuario = ?
            ORDER BY sv.fecha_solicitud DESC";
        $stmt = $this->executeQuery($sql, array($id_usuario));
        return $this->getResults($stmt);
    }

    public function getCategories()
    {
        $sql = "SELECT * FROM CATEGORIAS WHERE estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getSocialNetworks($id_usuario)
    {
        $sql = "SELECT tipo_red, url_perfil FROM REDES_SOCIALES WHERE id_usuario = ?";
        $stmt = $this->executeQuery($sql, array($id_usuario));
        $redes = $this->getResults($stmt);

        $resultado = array(
            'facebook' => '',
            'instagram' => '',
            'twitter' => '',
            'linkedin' => ''
        );

        foreach ($redes as $red) {
            if (isset($resultado[strtolower($red['tipo_red'])])) {
                $resultado[strtolower($red['tipo_red'])] = $red['url_perfil'];
            }
        }
        return array($resultado);
    }

    //FUNCIONES PARA PRODUCTOS DE LA VISTA [RODUCTOSVIEWS.PHP]
    public function getProductosByVendedor($id_vendedor)
    {
        $sql = "SELECT p.*, c.nombre_categoria, u.nombre AS nombre_usuario
                  FROM PRODUCTOS p
                  INNER JOIN CATEGORIAS c ON p.id_categoria = c.id_categoria
                  INNER JOIN USUARIOS u ON p.id_usuario = u.id_usuario
                  WHERE p.id_usuario = ?";
        $stmt = $this->executeQuery($sql, [$id_vendedor]);
        return $this->getResults($stmt);
    }
    public function verificarProducto($id_usuario, $codigo, $nombre_producto)
    {
        $sql = "SELECT id_producto FROM PRODUCTOS WHERE id_usuario = ? AND (codigo = ? OR nombre_producto = ?)";
        $stmt = $this->executeQuery($sql, array($id_usuario, $codigo, $nombre_producto));
        $result = $this->getResults($stmt);
        return !empty($result);
    }

    public function getAllProductosWithVendedor()
    {
        $sql = "SELECT p.*, c.nombre_categoria, u.nombre AS nombre_usuario
                  FROM PRODUCTOS p
                  INNER JOIN CATEGORIAS c ON p.id_categoria = c.id_categoria
                  INNER JOIN USUARIOS u ON p.id_usuario = u.id_usuario";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getAllCategorias()
    {
        $sql = "SELECT * FROM CATEGORIAS WHERE estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }


    public function newProduct(
        $id_usuario,
        $id_categoria,
        $codigo,
        $nombre_producto,
        $descripcion,
        $cantidad,
        $precio_venta,
        $precio_compra,
        $imagen = 'fotoproducto/default.jpg'
    ) {

        // 2. Preparar la consulta SQL con parámetros
        $sql = "INSERT INTO PRODUCTOS (
                        id_usuario, 
                        id_categoria, 
                        codigo, 
                        nombre_producto, 
                        descripcion, 
                        cantidad, 
                        precio_venta, 
                        precio_compra, 
                        imagen,
                        fecha_registro,
                        estado
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?,GETDATE(),'ACTIVO')";


        $params = array(
            $id_usuario,
            $id_categoria,
            $codigo,
            $nombre_producto,
            $descripcion,
            $cantidad,
            $precio_venta,
            $precio_compra,
            $imagen
        );

        return $this->executeNonQuery($sql, $params);
    }

    public function updateProducto(
        $id_categoria,
        $codigo,
        $nombre_producto,
        $descripcion,
        $cantidad,
        $precio_venta,
        $precio_compra,
        $id_producto
    ) {

        $sql = "UPDATE PRODUCTOS SET 
            id_categoria = ?,
            codigo = ?,
            nombre_producto = ?,
            descripcion = ?,
            cantidad = ?,
            precio_venta = ?,
            precio_compra = ?,
            fecha_registro = GETDATE()
            where id_producto=?";

        $params = array(
            $id_categoria,
            $codigo,
            $nombre_producto,
            $descripcion,
            $cantidad,
            $precio_venta,
            $precio_compra,
            $id_producto
        );
        return $this->executeNonQuery($sql, $params);
    }

    public function deleteProduct($id_producto)
    {
        $sql = "DELETE FROM PRODUCTOS WHERE id_producto = ?";

        // Asegúrate de pasar un array como parámetro
        return $this->executeNonQuery($sql, array($id_producto));
    }

    public function newReport(

        $id_producto,
        $id_usuario,
        $id_admin,
        $motivo,
        $comentarios,
        $accion_tomada,
        $tipo_reporte,
        $estado
    ) {
        $sql = "INSERT INTO REPORTES(
            tipo_reporte,id_producto, id_usuario_reportado, 
            id_administrador,
            motivo,  accion_tomada, comentarios,estado
            ) VALUES (?,?, ?, ?, ?,?, ?, ?)";
        $params = [
            $tipo_reporte,
            $id_producto,
            $id_usuario,
            $id_admin,
            $motivo,
            $accion_tomada,
            $comentarios,
            $estado
        ];

        return $this->executeNonQuery($sql, $params);
    }

    public function verificarReporte($id_producto)
    {
        $sql = "SELECT id_producto FROM REPORTES
        WHERE ID_PRODUCTO=?";
        $stmt = $this->executeQuery($sql, [$id_producto]);
        $result = $this->getResults($stmt);
        return !empty($result);
    }

    //FUNCION PARA ACCIONES
    public function desactivarProd($id_producto)
    {

        $sql = "UPDATE PRODUCTOS SET ESTADO='INACTIVO'
        WHERE ID_PRODUCTO=?";
        return $this->executeNonQuery($sql, $id_producto);
    }

    public function suspenderUser($id_usuario, $permanente = false)
    {
        $estado = $permanente ? 0 : 2; //0=BANEADO 2=SUSPENDIDO
        $sql = "UPDATE USUARIOS SET ACTIVO=?
         WHERE id_usuario=?";
        return $this->executeNonQuery($sql, array($estado, $id_usuario));
    }
    //FUNCION PARA REPORTESs
    public function getReportes()
    {
        $sql = "SELECT r.*, p.nombre_producto, u.nombre as nombre_reportado, 
                       a.nombre as nombre_administrador
                FROM REPORTES r
                JOIN PRODUCTOS p ON r.id_producto = p.id_producto
                JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
                JOIN USUARIOS a ON r.id_administrador = a.id_usuario
                ORDER BY r.fecha_reporte DESC";

        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getDetalleReporte($idReporte)
    {
        $sql = "SELECT r.*, p.nombre_producto, 
                       u.nombre as nombre_reportado, 
                       a.nombre as nombre_administrador
                FROM REPORTES r
                JOIN PRODUCTOS p ON r.id_producto = p.id_producto
                JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
                JOIN USUARIOS a ON r.id_administrador = a.id_usuario
                WHERE r.id_reporte = ?";

        $result = $this->executeQuery($sql, array($idReporte));
        //return $result[0] ?? null;
        return $this->getResults($result);
    }
    ///FUNCIONES DE SOLICITUDES

    /**
     * Obtiene todas las solicitudes de vendedor con información de usuario
     */
    public function getAllSellerRequestsPend($estado = null)
    {
        $sql = "SELECT sv.*, u.nombre, u.login, u.email, u.telefono, 
                  r.nombre_rol as rol_actual
            FROM SOLICITUDES_VENDEDOR sv
            JOIN USUARIOS u ON sv.id_usuario = u.id_usuario
            JOIN ROLES_USUARIO ru ON u.id_usuario = ru.id_usuario
            JOIN ROLES r ON ru.id_rol = r.id_rol
            WHERE (? IS NULL OR sv.estado = ?)
            ORDER BY sv.fecha_solicitud DESC";

        $params = $estado ? [$estado, $estado] : [null, null];
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }
    public function getAllSellerRequests($estado = null)
    {
        $sql = "SELECT sv.*, u.nombre, u.login, u.email, u.telefono,a.nombre as nombre_revisor,
                  r.nombre_rol as rol_actual
            FROM SOLICITUDES_VENDEDOR sv
            JOIN USUARIOS u ON sv.id_usuario = u.id_usuario 
            JOIN USUARIOS a ON sv.id_revisor = a.id_usuario
            JOIN ROLES_USUARIO ru ON u.id_usuario = ru.id_usuario
            JOIN ROLES r ON ru.id_rol = r.id_rol
            WHERE (? IS NULL OR sv.estado = ?)
            ORDER BY sv.fecha_solicitud DESC";

        $params = $estado ? [$estado, $estado] : [null, null];
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    /**
     * Actualiza el estado de una solicitud de vendedor
     */
    public function updateSellerRequestStatus($id_solicitud, $estado, $id_revisor, $comentarios = null)
    {
        $sql = "UPDATE SOLICITUDES_VENDEDOR 
                SET estado = ?, 
                    fecha_revision = GETDATE(), 
                    id_revisor = ?, 
                    comentarios = ?
                WHERE id_solicitud = ?";

        return $this->executeNonQuery($sql, [$estado, $id_revisor, $comentarios, $id_solicitud]);
    }

    /**
     * Cambia el rol de un usuario (para cuando se aprueba como vendedor)
     */
    public function changeUserRole($id_usuario, $id_nuevo_rol)
    {
        // Primero eliminamos cualquier rol existente para evitar duplicados
        $sqlDelete = "DELETE FROM ROLES_USUARIO WHERE id_usuario = ?";
        $this->executeNonQuery($sqlDelete, [$id_usuario]);

        // Luego insertamos el nuevo rol
        $sqlInsert = "INSERT INTO ROLES_USUARIO (id_usuario, id_rol) VALUES (?, ?)";
        return $this->executeNonQuery($sqlInsert, [$id_usuario, $id_nuevo_rol]);
    }

    /**
     * Obtiene una solicitud específica con detalles del usuario
     */
    public function getSellerRequestDetails($id_solicitud)
    {
        $sql = "SELECT sv.*, u.*, a.nombre as nombre_revisor, r.nombre_rol as rol_actual
                FROM SOLICITUDES_VENDEDOR sv
                JOIN USUARIOS u ON sv.id_usuario = u.id_usuario
                LEFT JOIN USUARIOS a ON sv.id_revisor = a.id_usuario
                JOIN ROLES_USUARIO ru ON u.id_usuario = ru.id_usuario
                JOIN ROLES r ON ru.id_rol = r.id_rol
                WHERE sv.id_solicitud = ?";

        $stmt = $this->executeQuery($sql, [$id_solicitud]);
        return $this->getResults($stmt)[0] ?? null;
    }

    
    //FUNCUINES PARA LOSS PRODUCTOS
    public function getProductosByCategoria($id_categoria = null)
    {
        $sql = "SELECT p.*, u.nombre as nombre_vendedor, c.nombre_categoria
            FROM PRODUCTOS p
            JOIN USUARIOS u ON p.id_usuario = u.id_usuario
            JOIN CATEGORIAS c ON p.id_categoria = c.id_categoria
            WHERE p.estado = 'ACTIVO' AND (? IS NULL OR p.id_categoria = ?)";

        $params = $id_categoria ? [$id_categoria, $id_categoria] : [null, null];
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getProductoById($id_producto)
    {
        $sql = "SELECT p.*, u.nombre as nombre_vendedor, c.nombre_categoria
            FROM PRODUCTOS p
            JOIN USUARIOS u ON p.id_usuario = u.id_usuario
            JOIN CATEGORIAS c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = ?";

        $stmt = $this->executeQuery($sql, [$id_producto]);
        return $this->getResults($stmt)[0] ?? null;
    }

    /*FUNCIONES DE CARRITOS*/
    public function obtenerCarritoActivo($id_usuario)
    {
        $sql = "SELECT id_carrito FROM CARRITO 
            WHERE id_usuario = ? AND estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql, [$id_usuario]);
        $result = $this->getResults($stmt);

        if (!empty($result)) {
            return $result[0]['id_carrito'];
        } else {
            // Crear nuevo carrito si no existe
            $sql = "INSERT INTO CARRITO (id_usuario) OUTPUT INSERTED.id_carrito VALUES (?)";
            $stmt = $this->executeQuery($sql, [$id_usuario]);
            $result = $this->getResults($stmt);
            return $result[0]['id_carrito'];
        }
    }

    public function agregarProductoCarrito($id_usuario, $id_producto, $cantidad = 1)
    {
        // Obtener precio actual del producto
        $producto = $this->getProductoById($id_producto);
        if (!$producto) {
            throw new Exception("Producto no encontrado");
        }

        $id_carrito = $this->obtenerCarritoActivo($id_usuario);

        // Verificar si el producto ya está en el carrito
        $sql = "SELECT id_detalle, cantidad FROM DETALLE_CARRITO 
            WHERE id_carrito = ? AND id_producto = ?";
        $stmt = $this->executeQuery($sql, [$id_carrito, $id_producto]);
        $existe = $this->getResults($stmt);

        if (!empty($existe)) {
            // Actualizar cantidad si ya existe
            $nueva_cantidad = $existe[0]['cantidad'] + $cantidad;
            $sql = "UPDATE DETALLE_CARRITO SET cantidad = ? 
                WHERE id_detalle = ?";
            return $this->executeNonQuery($sql, [$nueva_cantidad, $existe[0]['id_detalle']]);
        } else {
            // Agregar nuevo producto al carrito
            $sql = "INSERT INTO DETALLE_CARRITO 
                (id_carrito, id_producto, cantidad, precio_unitario) 
                VALUES (?, ?, ?, ?)";
            return $this->executeNonQuery($sql, [
                $id_carrito,
                $id_producto,
                $cantidad,
                $producto['precio_venta']
            ]);
        }
    }

    public function obtenerProductosCarrito($id_usuario)
    {
        $id_carrito = $this->obtenerCarritoActivo($id_usuario);

        $sql = "SELECT dc.*, p.nombre_producto, p.descripcion, p.imagen, 
                   (dc.cantidad * dc.precio_unitario) as subtotal
            FROM DETALLE_CARRITO dc
            JOIN PRODUCTOS p ON dc.id_producto = p.id_producto
            WHERE dc.id_carrito = ?";
        $stmt = $this->executeQuery($sql, [$id_carrito]);
        return $this->getResults($stmt);
    }

    public function actualizarCantidadCarrito($id_detalle, $cantidad)
    {
        $sql = "UPDATE DETALLE_CARRITO SET cantidad = ? 
            WHERE id_detalle = ? AND cantidad > 0";
        return $this->executeNonQuery($sql, [$cantidad, $id_detalle]);
    }

    public function eliminarProductoCarrito($id_detalle)
    {
        $sql = "DELETE FROM DETALLE_CARRITO WHERE id_detalle = ?";
        return $this->executeNonQuery($sql, [$id_detalle]);
    }

    public function vaciarCarrito($id_usuario)
    {
        $id_carrito = $this->obtenerCarritoActivo($id_usuario);
        $sql = "DELETE FROM DETALLE_CARRITO WHERE id_carrito = ?";
        return $this->executeNonQuery($sql, [$id_carrito]);
    }

    public function obtenerTotalCarrito($id_usuario)
    {
        $id_carrito = $this->obtenerCarritoActivo($id_usuario);

        $sql = "SELECT SUM(cantidad * precio_unitario) as total
            FROM DETALLE_CARRITO
            WHERE id_carrito = ?";
        $stmt = $this->executeQuery($sql, [$id_carrito]);
        $result = $this->getResults($stmt);
        return $result[0]['total'] ?? 0;
    }

    public function obtenerCantidadTotalCarrito($id_usuario)
    {
        $id_carrito = $this->obtenerCarritoActivo($id_usuario);

        $sql = "SELECT SUM(cantidad) as total_items
            FROM DETALLE_CARRITO
            WHERE id_carrito = ?";
        $stmt = $this->executeQuery($sql, [$id_carrito]);
        $result = $this->getResults($stmt);
        return $result[0]['total_items'] ?? 0;
    }

    // Funciones para valoraciones
    public function agregarValoracion($id_usuario, $id_producto, $calificacion, $comentario = null)
    {
        $sql = "INSERT INTO VALORACIONES 
            (id_usuario, id_producto, calificacion, comentario)
            VALUES (?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [
            $id_usuario,
            $id_producto,
            $calificacion,
            $comentario
        ]);
    }

    public function obtenerValoracionesProducto($id_producto)
    {
        $sql = "SELECT v.*, u.nombre, u.foto_perfil
            FROM VALORACIONES v
            JOIN USUARIOS u ON v.id_usuario = u.id_usuario
            WHERE v.id_producto = ? AND v.estado = 'ACTIVO'
            ORDER BY v.fecha_valoracion DESC";
        $stmt = $this->executeQuery($sql, [$id_producto]);
        return $this->getResults($stmt);
    }

    public function obtenerPromedioValoraciones($id_producto)
    {
        $sql = "SELECT AVG(calificacion) as promedio, COUNT(*) as total
            FROM VALORACIONES
            WHERE id_producto = ? AND estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql, [$id_producto]);
        $result = $this->getResults($stmt);
        return [
            'promedio' => round($result[0]['promedio'] ?? 0, 1),
            'total' => $result[0]['total'] ?? 0
        ];
    }

    public function usuarioYaValoroProducto($id_usuario, $id_producto)
    {
        $sql = "SELECT id_valoracion FROM VALORACIONES 
            WHERE id_usuario = ? AND id_producto = ?";
        $stmt = $this->executeQuery($sql, [$id_usuario, $id_producto]);
        $result = $this->getResults($stmt);
        return !empty($result);
    }
}
