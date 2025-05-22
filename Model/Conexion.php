<?php
class Conexion
{
    private $server;
    private $database;
    private $user;
    private $password;
    private $connection;
    private $useManagedIdentity;

    public function __construct($useManagedIdentity = false)
    {
        $this->server = "servidor-proyecto123.database.windows.net";
        $this->database = "prototipo";
        $this->useManagedIdentity = $useManagedIdentity;

        // Configuración de la conexión
        if ($this->useManagedIdentity) {
            $accessToken = $this->getAccessToken();

            $connectionInfo = array(
                "Database" => $this->database,
                "Authentication" => 6, // Authentication = Active Directory Managed Identity
                "AccessToken" => $accessToken,
                "CharacterSet" => "UTF-8",
                "TrustServerCertificate" => true,
                "LoginTimeout" => 5
            );
        } else {
            $this->user = "adminsql";
            $this->password = "CoC0Play$.";

            $connectionInfo = array(
                "Database" => $this->database,
                "UID" => $this->user,
                "PWD" => $this->password,
                "CharacterSet" => "UTF-8",
                "TrustServerCertificate" => true,
                "LoginTimeout" => 5
            );
        }

        // Conectar a SQL Server
        $this->connection = sqlsrv_connect($this->server, $connectionInfo);

        if (!$this->connection) {
            $errors = sqlsrv_errors();
            error_log("Error de conexión a SQL Server: " . print_r($errors, true));
            throw new Exception("No se puede conectar al servidor de base de datos.");
        }
    }

    private function getAccessToken()
    {
        $tokenResource = "https://database.windows.net/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://169.254.169.254/metadata/identity/oauth2/token?api-version=2018-02-01&resource=" . urlencode($tokenResource));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Metadata: true"));
        $response = curl_exec($ch);

        if (!$response) {
            die("❌ Error al consultar metadata service: " . curl_error($ch));
        }

        curl_close($ch);
        $result = json_decode($response, true);

        if (!isset($result['access_token'])) {
            die("❌ No se pudo obtener el token de acceso.");
        }

        return $result['access_token'];
    }

    public function getConnection()
    {
        return $this->connection;
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
    public  function executeNonQuery($sql, $params = array())
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
    public function getUserById($id_usuario)
    {
        $sql = "SELECT * FROM USUARIOS WHERE id_usuario = ?";
        $stmt = $this->executeQuery($sql, [$id_usuario]);
        $result = $this->getResults($stmt);
        return $result[0] ?? null;
    }

    // FUNCIONES PARA LOS USUARIOS 
    public function searchUser($login)
    {
        $sql = "SELECT id_usuario FROM USUARIOS WHERE login = ?";
        $stmt = $this->executeQuery($sql, [$login]);
        $result = $this->getResults($stmt);
        return !empty($result);
    }

    /**
     * Incrementa el contador de intentos fallidos de un usuario
     */
    public function incrementFailedAttempts($id_usuario)
    {
        $sql = "UPDATE USUARIOS SET 
            intentos_fallidos = intentos_fallidos + 1,
            ultimo_acceso = GETDATE()
            WHERE id_usuario = ?";
        return $this->executeNonQuery($sql, [$id_usuario]);
    }

    /**
     * Bloquea una cuenta por un tiempo determinado (en segundos)
     */
    public function blockAccount($id_usuario, $seconds)
    {
        $sql = "UPDATE USUARIOS SET 
            fecha_bloqueo = DATEADD(SECOND, ?, GETDATE()),
            intentos_fallidos = 0
            WHERE id_usuario = ?";
        return $this->executeNonQuery($sql, [$seconds, $id_usuario]);
    }

    /**
     * Resetea los intentos fallidos al hacer login correctamente
     */
    public function resetFailedAttempts($id_usuario)
    {
        $sql = "UPDATE USUARIOS SET 
            intentos_fallidos = 0,
            fecha_bloqueo = NULL,
            ultimo_acceso = GETDATE()
            WHERE id_usuario = ?";
        return $this->executeNonQuery($sql, [$id_usuario]);
    }

    /**
     * Obtiene información sobre el bloqueo de un usuario
     */
    public function getLoginAttemptsInfo($id_usuario)
    {
        $sql = "SELECT intentos_fallidos, fecha_bloqueo 
            FROM USUARIOS 
            WHERE id_usuario = ?";
        $stmt = $this->executeQuery($sql, [$id_usuario]);
        return $this->getResults($stmt)[0] ?? null;
    }
    //REGISTRAR NUEVO USUARIO
    public function registerUserWithRole($nombre, $login, $password, $foto_perfil, $telefono, $id_rol)
    {
        // Iniciar transacción para asegurar integridad
        sqlsrv_begin_transaction($this->connection);
        //HASHEAR LA CONTRASEÑA ANTES DE ALMACENARLA
        $hashedPassword = strtolower(hash('sha256', $password));

        try {
            // 1. Insertar el usuario y obtener el ID insertado directamente
            $sql_user = "INSERT INTO USUARIOS 
                    (nombre, login, password, foto_perfil, telefono, fecha_registro) 
                    OUTPUT INSERTED.id_usuario
                    VALUES (?, ?, ?, ?, ?, GETDATE())";
            $result = $this->executeQuery($sql_user, array($nombre, $login, $hashedPassword, $foto_perfil, $telefono));

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


    //FUNCION PARA OBTENER AL USUARIO JUNTO SU ROL
    //TODOS LOS DATOS DEL USUARIO
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


    //FUNCION PARA OBTENER EL MENU DL USUARIO

    public function getMenuByRol($id_rol)
    {
        $sql = "SELECT * FROM MENU
            WHERE (? = 0 OR id_rol = ? OR id_rol IS NULL)
            ORDER BY orden";
        $stmt = $this->executeQuery($sql, array($id_rol, $id_rol));
        return $this->getResults($stmt);
    }

    //CCREAR NUEVO USUARIO
    public function createUserWithRole($nombre, $login, $password, $foto_perfil, $email, $id_rol)
    {
        // Iniciar transacción para asegurar integridad
        sqlsrv_begin_transaction($this->connection);
        //hashear la contraseña antes de almacenarla
        $hashedPassword = strtolower(hash('sha256', $password));

        try {
            // 1. Insertar el usuario y obtener el ID insertado directamente
            $sql_user = "INSERT INTO USUARIOS 
                    (nombre, login, password, foto_perfil, email, fecha_registro) 
                    OUTPUT INSERTED.id_usuario
                    VALUES (?, ?, ?, ?, ?, GETDATE())";
            $result = $this->executeQuery($sql_user, array($nombre, $login, $hashedPassword, $foto_perfil, $email));

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
        //FUNCION HASH PARA LA NUEVA CONTRASEÑA
        $hashedPassword = strtolower(hash('sha256', $new_password));
        $sql = "UPDATE USUARIOS SET password = ? WHERE id_usuario = ?";
        return $this->executeNonQuery($sql, array($hashedPassword, $id_usuario));
    }

    public function updateSocialNetworks($id_usuario, $facebook, $instagram, $linkedin, $twitter)
    {
        // Primero verifica si ya existe registro
        $check = "SELECT * FROM REDES_SOCIALES WHERE id_usuario = ?";
        $exists = $this->getResults($this->executeQuery($check, array($id_usuario)));

        $success = true;

        $redes = [
            'facebook' => $facebook,
            'instagram' => $instagram,
            'linkedin' => $linkedin,
            'twitter' => $twitter
        ];

        foreach ($redes as $tipo => $url) {
            // Verificar si existe este tipo de red para el usuario
            $checkRed = "SELECT * FROM REDES_SOCIALES WHERE id_usuario = ? AND tipo_red = ?";
            $existeRed = $this->getResults($this->executeQuery($checkRed, array($id_usuario, $tipo)));

            if ($existeRed) {
                // Actualizar solo si la URL no está vacía
                if (!empty($url)) {
                    $sql = "UPDATE REDES_SOCIALES SET url_perfil = ? WHERE id_usuario = ? AND tipo_red = ?";
                    $success = $success && $this->executeNonQuery($sql, array($url, $id_usuario, $tipo));
                }
            } else {
                // Insertar solo si la URL no está vacía
                if (!empty($url)) {
                    $sql = "INSERT INTO REDES_SOCIALES (id_usuario, tipo_red, url_perfil) VALUES (?, ?, ?)";
                    $success = $success && $this->executeNonQuery($sql, array($id_usuario, $tipo, $url));
                }
            }
        }

        return $success;
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

    public function newReportforUser($id_producto, $id_usuario_reportado, $id_administrador, $motivo, $comentarios, $accion_tomada)
    {
        // Debug: Registrar parámetros recibidos
        error_log("Parámetros newReportforUser: " . print_r(func_get_args(), true));

        $sql = "INSERT INTO REPORTES (
            tipo_reporte, 
            id_producto, 
            id_usuario_reportado, 
            id_administrador,
            motivo,
            comentarios,
            accion_tomada,
            estado,
            fecha_reporte
        ) VALUES (
            'PRODUCTO',
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            'PENDIENTE',
            GETDATE()
        )";

        $params = [
            $id_producto,
            $id_usuario_reportado,
            $id_administrador,
            $motivo,
            $comentarios,
            $accion_tomada
        ];

        // Debug: Mostrar consulta completa
        $fullQuery = $sql;
        foreach ($params as $param) {
            $fullQuery = preg_replace('/\?/', "'" . $param . "'", $fullQuery, 1);
        }
        error_log("Consulta SQL: " . $fullQuery);

        // Ejecutar consulta
        $result = $this->executeNonQuery($sql, $params);

        // Debug: Resultado
        error_log("Resultado ejecución: " . ($result ? 'Éxito' : 'Falló'));

        return $result;
    }

    public function idVendedor($id_producto)
    {
        $sql = "SELECT id_usuario FROM PRODUCTOS WHERE id_producto = ?";
        $stmt = $this->executeQuery($sql, [$id_producto]);
        $result = $this->getResults($stmt);
        return $result[0]['id_usuario'] ?? null;
    }

    public function verificarReporteExistente($id_usuario, $id_producto)
    {
        $sql = "SELECT id_reporte FROM REPORTES 
                WHERE tipo_reporte = 'PRODUCTO' 
                AND id_producto = ? 
                AND id_usuario_reportado = ?";
        $stmt = $this->executeQuery($sql, [$id_producto, $id_usuario]);
        $result = $this->getResults($stmt);
        return !empty($result);
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
        $sql = "SELECT 
                r.*, 
                p.nombre_producto, 
                u.nombre as nombre_reportado, 
                a.nombre as nombre_administrador,
                o.id_orden as id_orden
            FROM REPORTES r
            LEFT JOIN PRODUCTOS p ON r.id_producto = p.id_producto
            LEFT JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
            LEFT JOIN USUARIOS a ON r.id_administrador = a.id_usuario
            LEFT JOIN ORDENES o ON r.id_orden = o.id_orden
            ORDER BY r.fecha_reporte DESC";

        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getReporteById($id)
    {
        $sql = "SELECT r.*, 
                   p.nombre_producto,
                   u.nombre as nombre_reportado,
                   a.nombre as nombre_administrador,
                   ur.id_rol as rol_reportado
            FROM REPORTES r
            LEFT JOIN PRODUCTOS p ON r.id_producto = p.id_producto
            LEFT JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
            LEFT JOIN USUARIOS a ON r.id_administrador = a.id_usuario
            LEFT JOIN ROLES_USUARIO ur ON u.id_usuario = ur.id_usuario
            WHERE r.id_reporte = ?";

        $stmt = $this->executeQuery($sql, [$id]);
        return $this->getResults($stmt)[0] ?? null;
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
            WHERE p.estado = 'ACTIVO' AND (? IS NULL OR p.id_categoria = ?)
            ORDER BY NEWID()";

        $params = $id_categoria ? [$id_categoria, $id_categoria] : [null, null];
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getProductoById($id_producto)
    {
        $sql = "SELECT p.*, u.nombre as nombre_vendedor, u.apellido as apellido_vendedor
,u.login as login_vendedor , c.nombre_categoria
            FROM PRODUCTOS p
            JOIN USUARIOS u ON p.id_usuario = u.id_usuario
            JOIN CATEGORIAS c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto =?";

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

    public function crearReporte(
        string $tipo,
        int $id_usuario_reportado,
        int $id_administrador,
        string $motivo,
        string $accion_tomada = 'PENDIENTE',
        string $estado = 'PENDIENTE',
        string $comentarios = '',
        ?int $id_producto = null,
        ?int $id_orden = null
    ): bool {
        $sql = "INSERT INTO REPORTES (
            tipo_reporte,
            id_producto,
            id_orden,
            id_usuario_reportado,
            id_administrador,
            motivo,
            accion_tomada,
            estado,
            comentarios,
            fecha_reporte
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";

        $params = [
            $tipo,
            $id_producto,
            $id_orden,
            $id_usuario_reportado,
            $id_administrador,
            $motivo,
            $accion_tomada,
            $estado,
            $comentarios
        ];

        return $this->executeNonQuery($sql, $params);
    }

    public function verificarReporteOrdenExistente($id_orden, $id_usuario)
    {
        $sql = "SELECT id_reporte FROM REPORTES 
            WHERE tipo_reporte = 'ORDEN' 
            AND id_orden = ? 
            AND id_administrador = ?";
        $stmt = $this->executeQuery($sql, [$id_orden, $id_usuario]);
        $result = $this->getResults($stmt);
        return !empty($result);
    }
    public function obtenerAdministradorActivo(): ?array
    {
        $sql = "SELECT TOP 1 id_usuario, nombre 
            FROM USUARIOS 
            WHERE id_rol = 1 AND activo = 1
            ORDER BY NEWID()"; // Aleatorio para distribución equitativa

        $stmt = $this->executeQuery($sql);
        return $this->getResults($stmt)[0] ?? null;
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

    public function obtenerProductosCarritoAgrupados($id_usuario)
    {
        $id_carrito = $this->obtenerCarritoActivo($id_usuario);

        $sql = "SELECT 
                dc.*, 
                p.nombre_producto, 
                p.descripcion, 
                p.imagen, 
                p.id_usuario as id_vendedor,
                u.nombre as nombre_vendedor,
                (dc.cantidad * dc.precio_unitario) as subtotal
            FROM DETALLE_CARRITO dc
            JOIN PRODUCTOS p ON dc.id_producto = p.id_producto
            JOIN USUARIOS u ON p.id_usuario = u.id_usuario
            WHERE dc.id_carrito = ?
            ORDER BY p.id_usuario";

        $stmt = $this->executeQuery($sql, [$id_carrito]);
        $productos = $this->getResults($stmt);

        // Agrupar por vendedor
        $agrupados = [];
        foreach ($productos as $producto) {
            $id_vendedor = $producto['id_vendedor'];
            if (!isset($agrupados[$id_vendedor])) {
                $agrupados[$id_vendedor] = [
                    'vendedor' => [
                        'id' => $id_vendedor,
                        'nombre' => $producto['nombre_vendedor']
                    ],
                    'productos' => [],
                    'subtotal' => 0
                ];
            }
            $agrupados[$id_vendedor]['productos'][] = $producto;
            $agrupados[$id_vendedor]['subtotal'] += $producto['subtotal'];
        }

        return array_values($agrupados);
    }


    public function actualizarComentarioCarrito($id_detalle, $comentario)
    {
        $sql = "UPDATE DETALLE_CARRITO SET comentario = ? WHERE id_detalle = ?";
        return $this->executeNonQuery($sql, [$comentario, $id_detalle]);
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




    //funciones para chechout
    // Agrega estos métodos en tu clase Conexion

    public function crearOrden($id_usuario, $id_carrito, $total, $direccion = '', $comentarios_generales  = '')
    {
        sqlsrv_begin_transaction($this->connection);

        try {
            // 1. Obtener todos los productos del carrito con sus vendedores
            $sqlProductos = "SELECT dc.id_producto, p.id_usuario as id_vendedor, dc.cantidad, dc.precio_unitario
            FROM DETALLE_CARRITO dc
            JOIN PRODUCTOS p ON dc.id_producto = p.id_producto
            WHERE dc.id_carrito = ?";

            $stmtProductos = $this->executeQuery($sqlProductos, [$id_carrito]);
            $productos = $this->getResults($stmtProductos);

            if (empty($productos)) {
                throw new Exception("El carrito está vacío");
            }

            // Agrupar productos por vendedor
            $ordenesPorVendedor = [];
            foreach ($productos as $producto) {
                $id_vendedor = $producto['id_vendedor'];
                if (!isset($ordenesPorVendedor[$id_vendedor])) {
                    $ordenesPorVendedor[$id_vendedor] = [];
                }
                $ordenesPorVendedor[$id_vendedor][] = $producto;
            }

            $idsOrdenes = [];

            // Crear una orden por cada vendedor
            foreach ($ordenesPorVendedor as $id_vendedor => $productosVendedor) {
                // Calcular subtotal para este vendedor
                $subtotal = array_reduce($productosVendedor, function ($carry, $item) {
                    return $carry + ($item['cantidad'] * $item['precio_unitario']);
                }, 0);

                // 2. Crear la orden para este vendedor
                $sqlOrden = "INSERT INTO ORDENES 
                (id_usuario, id_vendedor, id_carrito, total, direccion_entrega, comentarios)
                OUTPUT INSERTED.id_orden
                VALUES (?, ?, ?, ?, ?, ?)";

                $paramsOrden = [
                    $id_usuario,
                    $id_vendedor,
                    $id_carrito,
                    $subtotal,
                    $direccion,
                    $comentarios_generales
                ];

                $stmt = $this->executeQuery($sqlOrden, $paramsOrden);
                $result = $this->getResults($stmt);
                $id_orden = $result[0]['id_orden'];
                $idsOrdenes[] = $id_orden;

                // 3. Mover los items del carrito a detalle_orden para este vendedor
                $sqlItems = "INSERT INTO DETALLE_ORDEN 
        (id_orden, id_producto, cantidad, precio_unitario, comentario)
        SELECT ?, id_producto, cantidad, precio_unitario, comentario
        FROM DETALLE_CARRITO
        WHERE id_carrito = ? AND id_producto IN (" .
                    implode(',', array_column($productosVendedor, 'id_producto')) . ")";

                $this->executeNonQuery($sqlItems, [$id_orden, $id_carrito]);

                // 4. Actualizar inventario para los productos de este vendedor
                $sqlInventario = "UPDATE PRODUCTOS p
                         SET cantidad = cantidad - dc.cantidad
                         FROM DETALLE_CARRITO dc
                         WHERE p.id_producto = dc.id_producto
                         AND dc.id_carrito = ?
                         AND p.id_usuario = ?";

                $this->executeNonQuery($sqlInventario, [$id_carrito, $id_vendedor]);
            }

            // 5. Marcar carrito como completado
            $this->executeNonQuery(
                "UPDATE CARRITO SET estado = 'COMPLETADO' WHERE id_carrito = ?",
                [$id_carrito]
            );

            sqlsrv_commit($this->connection);
            return $idsOrdenes; // Retornar array de IDs de órdenes creadas
        } catch (Exception $e) {
            sqlsrv_rollback($this->connection);
            error_log("Error al crear orden: " . $e->getMessage());
            return false;
        }
    }




    public function getOrdenById($id_orden)
    {
        $sql = "SELECT 
                    o.*,
                    c.nombre as cliente_nombre,
                    c.login as cliente_login,
                    c.direccion as cliente_direccion,
                    c.telefono as cliente_telefono,
                    v.nombre as vendedor_nombre
                FROM ORDENES o
                JOIN USUARIOS c ON o.id_usuario = c.id_usuario
                LEFT JOIN USUARIOS v ON o.id_vendedor = v.id_usuario
                WHERE o.id_orden = ?";

        $stmt = $this->executeQuery($sql, [$id_orden]);
        return $this->getResults($stmt)[0] ?? null;
    }

    public function getDetalleOrden($id_orden)
    {
        $sql = "SELECT d.*, p.nombre_producto, p.imagen
            FROM DETALLE_ORDEN d
            JOIN PRODUCTOS p ON d.id_producto = p.id_producto
            WHERE d.id_orden = ?";
        $stmt = $this->executeQuery($sql, [$id_orden]);
        return $this->getResults($stmt);
    }


    //OBTENER ORDENES Y ACTUALIZAR ESTADO

    public function actualizarEstadoOrden($id_orden, $nuevo_estado)
    {
        $sql = "UPDATE ORDENES SET estado = ? WHERE id_orden = ?";
        return $this->executeNonQuery($sql, [$nuevo_estado, $id_orden]);
    }

    /**
     * Obtiene el historial completo de órdenes para administradores
     */
    public function getHistorialCompleto($filtro_estado = null)
    {
        $sql = "SELECT 
                    o.*,
                    c.nombre as cliente_nombre,
                    c.login as cliente_login,
                    v.nombre as vendedor_nombre,
                    (SELECT COUNT(*) FROM DETALLE_ORDEN do WHERE do.id_orden = o.id_orden) as total_productos
                FROM ORDENES o
                JOIN USUARIOS c ON o.id_usuario = c.id_usuario
                LEFT JOIN USUARIOS v ON o.id_vendedor = v.id_usuario
                WHERE (? IS NULL OR o.estado = ?)
                ORDER BY o.fecha_orden DESC";

        $params = $filtro_estado ? [$filtro_estado, $filtro_estado] : [null, null];
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    /**
     * Obtiene el historial de un cliente específico
     */
    public function getHistorialCliente($id_usuario, $filtro_estado = null)
    {
        $sql = "SELECT 
                    o.*,
                    v.nombre as vendedor_nombre,
                    (SELECT COUNT(*) FROM DETALLE_ORDEN do WHERE do.id_orden = o.id_orden) as total_productos
                FROM ORDENES o
                LEFT JOIN USUARIOS v ON o.id_vendedor = v.id_usuario
                WHERE o.id_usuario = ?
                AND (? IS NULL OR o.estado = ?)
                ORDER BY o.fecha_orden DESC";

        $params = [$id_usuario];
        $params = array_merge($params, $filtro_estado ? [$filtro_estado, $filtro_estado] : [null, null]);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }

    public function getHistorialVendedor($id_vendedor, $filtro_estado = null)
    {
        $sql = "SELECT 
                    o.*,
                    c.nombre as cliente_nombre,
                    c.login as cliente_login,
                    (SELECT COUNT(*) FROM DETALLE_ORDEN do WHERE do.id_orden = o.id_orden) as total_productos
                FROM ORDENES o
                JOIN USUARIOS c ON o.id_usuario = c.id_usuario
                WHERE o.id_vendedor = ?
                AND (? IS NULL OR o.estado = ?)
                ORDER BY o.fecha_orden DESC";

        $params = [$id_vendedor];
        $params = array_merge($params, $filtro_estado ? [$filtro_estado, $filtro_estado] : [null, null]);
        $stmt = $this->executeQuery($sql, $params);
        return $this->getResults($stmt);
    }
    /**
     * Obtiene los detalles de una orden específica
     */
    public function getDetallesOrdenCompleto($id_orden)
    {
        $sql = "SELECT 
                do.*, 
                p.nombre_producto,
                p.imagen,
                u.nombre as vendedor_nombre
            FROM DETALLE_ORDEN do
            JOIN PRODUCTOS p ON do.id_producto = p.id_producto
            JOIN USUARIOS u ON p.id_usuario = u.id_usuario
            WHERE do.id_orden = ?";

        $stmt = $this->executeQuery($sql, [$id_orden]);
        return $this->getResults($stmt);
    }
}


class DatabaseConnectionException extends Exception {}
