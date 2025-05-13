<?php
require_once('../Model/Conexion.php');
require('Constants.php');

class ProfileController
{
    private $con;
    private $session;
    private $urlViews;

    private $userId;
    private $userRole;
    private $alert;
    private $message;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->con = new Conexion();
        $this->urlViews = URL_VIEWS;
        $this->alert = $_SESSION['alerta'] ?? '';
        $this->message = $_SESSION['mensaje'] ?? '';

        $this->checkAuthentication();
        $this->initializeUserData();
    }

    private function checkAuthentication()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: ../index.php");
            exit();
        }
    }

    private function initializeUserData()
    {

        $this->userId = $_SESSION['usuario']['id_usuario'];
        $this->userRole = strtolower($_SESSION['usuario']['rol']['nombre_rol']);
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        }

        $this->renderView();
    }

    private function handlePostRequest()
    {
        try {
            if (!isset($_POST['action'])) {
                throw new Exception("Acción no especificada");
            }

            switch ($_POST['action']) {
                case 'update_profile':
                    $this->updateProfile();
                    break;
                case 'update_password':
                    $this->updatePassword();
                    break;
                case 'update_social':
                    $this->updateSocialNetworks();
                    break;
                case 'seller_request':
                    $this->handleSellerRequest();
                    break;
                default:
                    throw new Exception("Acción no válida");
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    private function updateProfile()
    {
        $this->checkUpdateCooldown();

        $data = $this->prepareProfileData();
        $this->handleProfileImage($data);

        if (!$this->con->updateUserProfile(
            $this->userId,
            $data['email'],
            $data['nombre'],
            $data['apellido'],
            $data['telefono'],
            $data['direccion'],
            $data['fecha_nacimiento'],
            $data['genero'],
            $data['foto_perfil']
        )) {
            throw new Exception("Error al actualizar el perfil");
        }

        $this->updateSessionData($data);
        $this->setSuccess("Elementos actualizados correctamente");
        $this->redirect();
    }

    private function prepareProfileData()
    {
        return [
            'email' => trim($_POST['email'] ?? null),
            'nombre' => trim($_POST['nombre'] ?? null),
            'apellido' => trim($_POST['apellido'] ?? null),
            'telefono' => trim($_POST['telefono'] ?? null),
            'direccion' => trim($_POST['direccion'] ?? null),
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'genero' => $_POST['genero'] ?? null,
            'foto_perfil' => $_SESSION['usuario']['foto'] // Valor por defecto
        ];
    }

    private function handleProfileImage(&$data)
    {
        if (!empty($_FILES['foto']['name'])) {
            $uploadDir = '../Views/fotoproducto/';
            $foto = 'user_' . $this->userId . '_' . time() . '_' . basename($_FILES['foto']['name']);
            $uploadFile = $uploadDir . $foto;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                $data['foto_perfil'] = "fotoproducto/" . $foto;
            }
        }
    }

    public function updateSessionData($data)
    {
        $_SESSION['usuario']['email'] = $data['email'];
        $_SESSION['usuario']['nombre'] = $data['nombre'];
        $_SESSION['usuario']['apellido'] = $data['apellido'];
        $_SESSION['usuario']['telefono'] = $data['telefono'];
        $_SESSION['usuario']['direccion'] = $data['direccion'];
        $_SESSION['usuario']['fecha_nacimiento'] = $data['fecha_nacimiento'];
        $_SESSION['usuario']['genero'] = $data['genero'];
        $_SESSION['usuario']['foto'] = $data['foto_perfil'];

        $_SESSION['ultima_actualizacion_perfil'][$this->userId] = date('Y-m-d H:i:s');
    }

    private function updatePassword()
    {
        $this->checkUpdateCooldown();
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        $user = $this->con->getUser($_SESSION['usuario']['login']);

        $hashedInput = hash('sha256', utf8_encode($currentPassword));
        if (strcasecmp($hashedInput, $user[0]['password']) !== 0) {
            throw new Exception("La contraseña no coincide con la ingresada");
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception("Las contraseñas ingresadas no coinciden");
        }

        // Obtener usuario actual
        $user = $this->con->getUser($_SESSION['usuario']['login']);
        if (!$user) {
            throw new Exception("Error al verificar credenciales");
        }


        // Actualizar contraseña
        if (!$this->con->updatePassword($_SESSION['usuario']['id_usuario'], $newPassword)) {
            throw new Exception("Error al actualizar la contraseña en la base de datos");
        }
        $_SESSION['ultima_actualizacion_perfil'][$this->userId] = date('Y-m-d H:i:s');
        $this->setSuccess("Contraseña actualizada");
        $this->redirect();
    }

    private function updateSocialNetworks()
    {
        $this->checkUpdateCooldown();
        $facebook = $_POST['facebook'] ?? '';
        $instagram = $_POST['instagram'] ?? '';
        $twitter = $_POST['twitter'] ?? '';
        $linkedin = $_POST['linkedin'] ?? '';

        if (empty($facebook) && empty($instagram) && empty($twitter) && empty($linkedin)) {
        throw new Exception("Debe proporcionar al menos una red social");
    }

        
        if (!empty($facebook) && !filter_var($facebook, FILTER_VALIDATE_URL)) {
            throw new Exception("La URL de Facebook no es válida");
        }
        if (!empty($facebook) && !filter_var($instagram, FILTER_VALIDATE_URL)) {
            throw new Exception("La URL de Facebook no es válida");
        }
        if (!empty($facebook) && !filter_var($twitter, FILTER_VALIDATE_URL)) {
            throw new Exception("La URL de Facebook no es válida");
        }
        if (!empty($facebook) && !filter_var($linkedin, FILTER_VALIDATE_URL)) {
            throw new Exception("La URL de Facebook no es válida");
        }


        // Actualizar en base de datos
        if (!$this->con->updateSocialNetworks(
            $_SESSION['usuario']['id_usuario'],
            $facebook,
            $instagram,
            $linkedin,
            $twitter
        )) {
            throw new Exception("Error al actualizar las redes sociales");
        }
        $this->setSuccess("Perfiles actualizados correctamente");
        $this->redirect();
    }

    private function handleSellerRequest()
    {
        if ($this->userRole !== 'cliente') {
            throw new Exception("Acción no permitida para tu rol");
        }

        $idCategoria = (int)($_POST['categoria'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($idCategoria)) {
            throw new Exception("Debes seleccionar una categoría");
        }

        if (empty($descripcion)) {
            throw new Exception("Debes proporcionar una descripción");
        }

        if ($this->hasPendingRequest()) {
            throw new Exception("Ya tienes una solicitud pendiente");
        }

        $this->con->createSellerRequest($this->userId, $idCategoria, $descripcion);
        $_SESSION['ultima_actualizacion_perfil'][$this->userId] = date('Y-m-d H:i:s');
        $this->setSuccess("SOLICITUD CREADA CORRECTAMENTE");
        $this->redirect();
    }

    private function hasPendingRequest()
    {
        $solicitudes = $this->con->getSolicitudesUsuario($this->userId);
        foreach ($solicitudes as $solicitud) {
            if ($solicitud['estado'] === 'PENDIENTE') {
                return true;
            }
        }
        return false;
    }

    private function checkUpdateCooldown()
    {
        if (isset($_SESSION['ultima_actualizacion_perfil'][$this->userId])) {
            $ultimaActualizacion = strtotime($_SESSION['ultima_actualizacion_perfil'][$this->userId]);
            $ahora = time();
            $diferenciaSegundos = $ahora - $ultimaActualizacion;
            $unaHoraEnSegundos = 3600;

            if ($diferenciaSegundos < $unaHoraEnSegundos) {
                throw new Exception("Debes esperar una hora antes de poder actualizar tu perfil nuevamente.");
            }
        }
    }

    private function setError($message)
    {
        $_SESSION['mensaje'] = $message;
        $_SESSION['alerta'] = "alert-danger";
    }

    private function setSuccess($message)
    {
        $_SESSION['mensaje'] = $message;
        $_SESSION['alerta'] = "alert-success";
    }

    private function redirect()
    {
        header("Location: PerfilController.php");
        exit();
    }

    private function renderView()
    {
        $viewData = $this->prepareViewData();
        require('../Views/Perfil.php');
    }

    private function prepareViewData()
    {
        return [
            'user_data' => $this->getUserData(),
            'social_data' => $this->getSocialData(),
            'categories' => $this->getCategories(),
            'solicitudes' => $this->getSolicitudes(),
            'urlViews' => $this->urlViews,
            'alerta' => $this->alert,
            'mensaje' => $this->message
        ];
    }

    private function getUserData()
    {
        return [
            'id_usuario' => $_SESSION['usuario']['id_usuario'],
            'login' => $_SESSION['usuario']['login'],
            'email' => $_SESSION['usuario']['email'] ?? '',
            'nombre' => $_SESSION['usuario']['nombre'],
            'apellido' => $_SESSION['usuario']['apellido'] ?? '',
            'telefono' => $_SESSION['usuario']['telefono'] ?? '',
            'direccion' => $_SESSION['usuario']['direccion'] ?? '',
            'fecha_nacimiento' => $_SESSION['usuario']['fecha_nacimiento'],
            'genero' => $_SESSION['usuario']['genero'] ?? '',
            'foto_perfil' => $_SESSION['usuario']['foto'] ?? 'fotoproducto/user.png',
            'fecha_registro' => $_SESSION['usuario']['fecha_registro'],
            'ultimo_acceso' => $_SESSION['usuario']['ultimo_acceso'] ?? '',
            'activo' => $_SESSION['usuario']['activo'] ?? 1,
            'verificado' => $_SESSION['usuario']['verificado'] ?? 0
        ];
    }

    private function getSocialData()
    {
        $socialData = $this->con->getSocialNetworks($this->userId);
        return !empty($socialData) ? $socialData[0] : [
            'facebook' => '',
            'instagram' => '',
            'linkedin' => '',
            'twitter' => ''
        ];
    }

    private function getCategories()
    {
        return $this->userRole === 'cliente' ? $this->con->getCategories() : [];
    }

    private function getSolicitudes()
    {
        return $this->userRole === 'cliente' ? $this->con->getSolicitudesUsuario($this->userId) : [];
    }
}

// Ejecutar el controlador
try {
    $controller = new ProfileController();
    $controller->handleRequest();
} catch (Throwable $e) { // Captura tanto Exception como Error
    // Registrar el error en logs
    error_log("Error crítico: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());

    // Iniciar sesión si no está iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION['mensaje'] = "Error en el sistema. Por favor intente más tarde.";
    $_SESSION['alerta'] = "alert-danger";
    
    header("Location: PerfilController.php");
    exit();
}
