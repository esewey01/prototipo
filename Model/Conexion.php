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
    public function executeQuery($sql, $params = array())
    {
        $stmt = sqlsrv_query($this->connection, $sql, $params);

        if ($stmt === false) {
            die("Error en consulta: " . print_r(sqlsrv_errors(), true));
            return false;
        }

        return $stmt;
    }

    public function getResults($stmt)
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

    public function executeNonQuery($sql, $params = array())
    {
        $stmt = sqlsrv_query($this->connection, $sql, $params);

        if ($stmt === false) {
            die("Error en operación: " . print_r(sqlsrv_errors(), true));
        }

        $rowsAffected = sqlsrv_rows_affected($stmt);
        sqlsrv_free_stmt($stmt);
        return $rowsAffected;
    }


    
    //FUNCION PARA OBTENER EL ULTIMO ID
    public function getLastInsertId() {
        $sql = "SELECT SCOPE_IDENTITY() AS last_id";
        $stmt = $this->executeQuery($sql);
        $result = $this->getResults($stmt);
        return $result[0]['last_id'];
    }




    // FUNCIONES PARA LOS UUSARIOS 
    public function getUser($login)
    {
        $sql = "SELECT * FROM USUARIOS WHERE login = ?";
        $stmt = $this->executeQuery($sql, array($login));
        return $this->getResults($stmt);
    }

    //FUNCION PARA OBTENER EL ROL DEL USUARIO
    public function getRolUser($id_usuario) {
        $sql = "SELECT r.id_rol, r.nombre_rol 
                FROM ROLES_USUARIO ru
                JOIN ROLES r ON ru.id_rol = r.id_rol
                WHERE ru.id_usuario = ?";
        $stmt = $this->executeQuery($sql, array($id_usuario));
        $result = $this->getResults($stmt);
        return $result[0] ?? null; // Retorna el primer rol encontrado o null
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
public function getAllUsersWithRoles() {
    $sql = "SELECT u.*, r.nombre_rol 
            FROM USUARIOS u
            JOIN ROLES_USUARIO ru ON u.id_usuario = ru.id_usuario
            JOIN ROLES r ON ru.id_rol = r.id_rol
            ORDER BY r.nombre_rol, u.nombre";
    $stmt = $this->executeQuery($sql);
    return $this->getResults($stmt);
}

// Obtener usuarios por tipo de rol
public function getUsersByRoleType($roleType) {
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
public function isSuperUser($userId) {
    $sql = "SELECT COUNT(*) as is_super 
            FROM ROLES_USUARIO ru
            JOIN ROLES r ON ru.id_rol = r.id_rol
            WHERE ru.id_usuario = ? AND r.nombre_rol = 'SUPER_USER'";
    $stmt = $this->executeQuery($sql, array($userId));
    $result = $this->getResults($stmt);
    return ($result[0]['is_super'] > 0);
}

    public function getMenuByRol($id_rol)
    {
        $sql = "SELECT * FROM MENU 
            WHERE id_rol = ? OR id_rol IS NULL
            ORDER BY orden";
        $stmt = $this->executeQuery($sql, array($id_rol));
        return $this->getResults($stmt);
    }

    

    // En tu clase Conexion.php
    public function updateUserProfile($id_usuario, $data) {
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
        $params = array($new_password, $id_usuario);
        return $this->executeNonQuery($sql, $params);
    }

    public function updateSocialNetworks($id_usuario, $facebook, $instagram,$linkedin, $twitter)
    {
        // Primero verifica si ya existe registro
        $check = "SELECT * FROM REDES_SOCIALES WHERE id_usuario = ?";
        $exists = $this->getResults($this->executeQuery($check, array($id_usuario)));

        if ($exists) {
            $sql = "UPDATE REDES_SOCIALES SET url = ? WHERE id_usuario = ? AND tipo = ?";
            $this->executeNonQuery($sql, array($facebook, $id_usuario, 'facebook'));
            $this->executeNonQuery($sql, array($instagram, $id_usuario, 'instagram'));
            $this->executeNonQuery($sql, array($linkedin, $id_usuario, 'linkedin'));
            $this->executeNonQuery($sql, array($twitter, $id_usuario, 'twitter'));
        } else {
            $sql = "INSERT INTO REDES_SOCIALES (id_usuario, tipo, url) VALUES (?, ?, ?)";
            $this->executeNonQuery($sql, array($id_usuario, 'facebook', $facebook));
            $this->executeNonQuery($sql, array($id_usuario, 'instagram', $instagram));
            $this->executeNonQuery($sql, array($id_usuario, 'linkedin',$linkedin));
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
        $sql = "SELECT sv.*, c.nombre as categoria 
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

    public function getSocialNetworks($id_usuario) {
        $sql = "SELECT tipo_red, url_perfil FROM REDES_SOCIALES WHERE id_usuario = ?";
        $stmt = $this->executeQuery($sql, array($id_usuario));
        $redes = $this->getResults($stmt);
        
        $resultado = array(
            'facebook' => '',
            'instagram' => '',
            'twitter' => '',
            'linkedin'=>''
        );
        
        foreach ($redes as $red) {
            if (isset($resultado[strtolower($red['tipo_red'])])) {
                $resultado[strtolower($red['tipo_red'])] = $red['url_perfil'];
            }
        }

        
        
        
        return array($resultado);
    }
    
    
}
