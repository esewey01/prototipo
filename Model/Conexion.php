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
        $this->server = "MPMLW10DELF6Z2";
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
            $result = $this->executeQuery($sql_user, array($nombre, $login, $password, $foto_perfil,$telefono));

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




    // En tu clase Conexion.php
    public function updateUserProfile($id_usuario, $data)
    {
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
            $data['email'],
            $data['nombre'],
            $data['apellido'],
            $data['telefono'],
            $data['direccion'],
            $data['fecha_nacimiento'],
            $data['genero'],
            $data['foto_perfil'],
            $id_usuario
        );

        return $this->executeNonQuery($sql, $params);
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
    ) {
        $sql = "INSERT INTO REPORTES(
            id_producto, id_usuario_reportado, id_administrador,
            motivo, comentarios, accion_tomada, estado
            ) VALUES (?, ?, ?, ?,?, ?, 'PROCESADO')";
        $params = [
            $id_producto,
            $id_usuario,
            $id_admin,
            $motivo,
            $comentarios,
            $accion_tomada,
        ];

        return $this->executeNonQuery($sql, $params);
    }

    //FUNCION PARA ACCIONES
    public function desactivarProd($id_producto){
        $sql="UPDATE PRODUCTOS SET ESTADO='INACTIVO'
        WHERE ID_PRODUCTO=?";
        return $this->executeNonQuery($sql, $id_producto);
    }
    
    public function suspenderUser($id_usuario){
        $sql="UPDATE USUARIOS SET ACTIVO=0,
         WHERE id_usuario=?";
         return $this->executeNonQuery($sql,$id_usuario);
    }
    //FUNCION PARA REPORTES
    public function getReportes()
    {
        $sql = "SELECT r.*, p.nombre_producto, u.nombre as nombre_reportado, 
                       a.nombre as nombre_administrador
                FROM REPORTES r
                JOIN PRODUCTOS p ON r.id_producto = p.id_producto
                JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
                JOIN USUARIOS a ON r.id_administrador = a.id_usuario
                ORDER BY r.fecha_reporte DESC";

        $stmt= $this->executeQuery($sql);
        return $this->getResults($stmt);
    }

    public function getDetalleReporte($idReporte) {
        $sql = "SELECT r.*, p.nombre_producto, 
                       u.nombre as nombre_reportado, 
                       a.nombre as nombre_administrador
                FROM REPORTES r
                JOIN PRODUCTOS p ON r.id_producto = p.id_producto
                JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
                JOIN USUARIOS a ON r.id_administrador = a.id_usuario
                WHERE r.id_reporte = ?";
        
        $result = $this->executeQuery($sql,array($idReporte));
//return $result[0] ?? null;
     return $this->getResults($result);
    }
}
