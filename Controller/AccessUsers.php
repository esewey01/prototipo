<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

class AuthController
{
    private $conexion;

    private $alerta;
    private $mensaje;

    public function __construct(Conexion $conexion)
    {
        $this->conexion = $conexion;
        $this->alerta = $_SESSION['alerta'] ?? '';
        $this->mensaje = $_SESSION['mensaje'] ?? '';
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->setErrorSession("Acceso no autorizado");
            $this->redirectToLogin();
            return;
        }

        $login = trim($_POST['login']);
        $password = trim($_POST['password']);

        if (!$this->validateInputs($login, $password)) {
            return;
        }

        try {
            $this->authenticateUser($login, $password);
        } catch (Exception $e) {
            $this->setErrorSession($e->getMessage());
            $this->redirectToLogin();
        }
    }

    private function validateInputs($login, $password)
    {
        if (empty($login) || empty($password)) {
            $this->setErrorSession("Ningún campo debe estar vacío");
            $this->redirectToLogin();
            return false;
        }



        // Validación adicional contra inyección SQL
    if (preg_match('/[\'"\\;]/', $login)) {
        error_log("Intento de inyección SQL detectado: " . $login);
        $this->setErrorSession("Caracteres no permitidos");
        $this->redirectToLogin();
        return false;
    }
     // Longitud máxima razonable
     if (strlen($login) > 50 || strlen($password) > 100) {
        $this->setErrorSession("Datos de entrada demasiado largos");
        $this->redirectToLogin();
        return false;
    }
        return true;
    }

    private function authenticateUser($login, $password)
    {
        $usuario = $this->conexion->getUserWithRole($login);

        if ($usuario === null) {
            // Esto podría indicar un error en la consulta, no solo "usuario no existe"
            error_log("Error al obtener usuario: posible problema de conexión");
            throw new Exception("Error en el sistema. Por favor intente más tarde.");
        }

        if (!$usuario) {
            //NO REVELAR SI EL USUARIO NO EXISTE
            throw new Exception("Usuario o contraseña incorrectos");
        }

        //VERIFICAR SI LA CUENTA ESTA BLOQUEDA TEMPORALMENTE

        if ($usuario['fecha_bloqueo'] && $usuario['fecha_bloqueo']->getTimestamp() > time()) {
            throw new Exception("Cuenta bloqueada temporalmente. Intenta más tarde.");
        }


        $hashedInput = hash('sha256', utf8_encode($password));

        // Comparar con el hash almacenado en la base de datos
        if (strcasecmp($hashedInput, $usuario['password']) !== 0) {
            // Incrementar intentos fallidos
            $this->conexion->incrementFailedAttempts($usuario['id_usuario']);

            // Bloquear después de 5 intentos por 30 minutos
            if ($usuario['intentos_fallidos'] + 1 >= 5) {
                $this->conexion->blockAccount($usuario['id_usuario'], 1800); // 30 minutos
                throw new Exception("Demasiados intentos fallidos. Cuenta bloqueada temporalmente.");
            }

            throw new Exception("Usuario o Password incorrectos, por favor intenta de nuevo1");
        }

        // Resetear intentos fallidos al loguearse correctamente
        $this->conexion->resetFailedAttempts($usuario['id_usuario']);



        $this->checkAccountStatus($usuario);
        $this->setupUserSession($usuario);
        $this->redirectBasedOnRole($usuario);
    }



    private function checkAccountStatus($usuario)
    {
        if (!$usuario['activo']) {
            throw new Exception("Tu cuenta está desactivada. Contacta al administrador");
        }
    }

    private function setupUserSession($usuario)
    {
        // Destruye la sesión anterior completamente
        session_unset();
        session_destroy();
        

        // Configura parámetros seguros de sesión
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1); // Solo si usas HTTPS
        ini_set('session.use_strict_mode', 1);

        session_start();
        session_regenerate_id(true); // Cambia el ID de sesión para prevenir ataques de fijación de sesión
        $_SESSION['LAST_ACTIVITY'] = time(); // Actualiza el tiempo de la última actividad

        $_SESSION['usuario'] = [
            'id_usuario' => $usuario['id_usuario'],
            'login' => $usuario['login'],
            'password' => $usuario['password'],
            'email' => $usuario['email'],
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'telefono' => $usuario['telefono'],
            'direccion' => $usuario['direccion'],
            'genero' => $usuario['genero'],
            'fecha_nacimiento' => ($usuario['fecha_nacimiento'] instanceof DateTime) ? $usuario['fecha_nacimiento']->format('Y-m-d') : '',
            'foto' => $usuario['foto_perfil'],
            'fecha_registro' => $usuario['fecha_registro']->format('Y-m-d H:i:s'),
            'ultimo_registro' => $usuario['ultimo_registro'] ?? ' ',
            'rol' => [
                'id_rol' => $usuario['id_rol'],
                'nombre_rol' => $usuario['nombre_rol']
            ],
            'menu' => $this->conexion->getMenuByRol($usuario['id_rol'])
        ];
    }

    private function redirectBasedOnRole($usuario)
    {
        $roleName = strtolower($usuario['nombre_rol']);
        $this->mensaje = strtoupper($roleName) . ": " . $usuario['nombre'];
        $_SESSION['mensaje'] = "Conexion exitosa, bienvenido a la plataforma";
        $_SESSION['alerta'] = "alert-success";

        $viewMap = [
            'superuser' => '../Views/Wellcome.php',
            'administrador' => '../Views/Wellcome.php',
            'vendedor' => '../Views/Wellcome.php',
            'cliente' => '../Views/Wellcome.php'
        ];

        if (!isset($viewMap[$roleName])) {
            throw new Exception("Rol no reconocido");
        }

        require($viewMap[$roleName]);
        unset($_SESSION['mensaje']);
        unset($_SESSION['alerta']);

        exit();
    }

    private function setErrorSession($message)
    {
        $_SESSION['mensaje'] = $message;
        $_SESSION['alerta'] = "alert-danger";
    }

    private function redirectToLogin()
    {

        header('Location: ../index.php');
        exit();
    }
}

// Al final del archivo, modifica la creación del controlador
try {
    $con = new Conexion();
    $authController = new AuthController($con);
    $authController->handleRequest();
} catch (Exception $e) {
    // Registrar el error en logs
    error_log("Error de conexión: " . $e->getMessage());

    // Mostrar mensaje amigable al usuario
    $_SESSION['mensaje'] = "Error de conexión. Por favor intente más tarde.";
    $_SESSION['alerta'] = "alert-danger";
    header('Location: ../index.php');
    exit();
}
