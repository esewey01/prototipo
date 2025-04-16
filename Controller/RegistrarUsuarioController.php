<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');
require_once __DIR__ . '/../config.php';


function validarNumero($x)
{
    $URL = "http://apilayer.net/api/validate";
    $access_key = "a6df017f3f16d30cdde0ec268fe259ec";
    $country_code = "MX";
    $format = 1;

    $consulta = http_build_query([
        'access_key' => $access_key,
        'number' => $x,
        'country_code' => $country_code,
        'format' => $format
    ]);

    //INICIALIZAR API
    $newUrl = $URL . "?" . $consulta;

    $consumo = file_get_contents($newUrl);
    $data = json_decode($consumo, true);

    if ($consumo === false) {
        return "false";
    } else {
        return $data['valid']; //ya sea true o false
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $con = new Conexion();
    $respuesta = ""; //VERIFICAR SI EL USUARIO YA EXISTE
    $errores = []; //VALIDACIONES
    $mensaje = [];


    if (!$con) {
        $errores[] = "Error de conexión a la base de datos";
    }

    $nombre = $_POST['nombre'] ?? '';
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $foto = $_FILES['foto']['name'] ?? '';
    $fotoPath =  'fotoproducto/user.png';
    $tipo = 'CLIENTE';

    //NINGUN VALOR VACÍO
    if (empty($login) || empty($password) || empty($password2) || empty($nombre) || empty($telefono)) {
        $errores[] = "Ningun campo debe estar vacío";
    }

    //VALIDAR TELEFONO
    if (preg_match('/^[0-9]\d{10}$/', $telefono) === false) {
        $errores[] = "El teléfono debe tener 10 dígitos y solo contener números";
    } else {
        if (!validarNumero($telefono)) {
            $errores[] = "El teléfono no es válido";//LO MANEJA COMO ERROR
        }
        else{
            $mensaje[] = " Teléfono válido";
        }
    }




    //BOLETA TIENE QUE SER MAYOR A 10 DIGITOS
    if (preg_match('/^[0-9]\d{10}$/', $login) === false) {
        $errores[] = "La boleta solo debe tener 10 digitos y solo contener números";
    }

    /*
        if (!empty($password) && strlen($password) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        }*/

    if ($password !== $password2) {
        $errores[] = "Las contraseñas no coinciden";
    }


    // Validación de imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $foto = $_FILES['foto'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $allowedTypes = ['image/jpeg', 'image/png'];

        if ($foto['error'] !== UPLOAD_ERR_OK) {
            $errores[] = "Error al subir la imagen";
        } elseif ($foto['size'] > $maxSize) {
            $errores[] = "La imagen es demasiado grande (máximo 2MB)";
        } elseif (!in_array($foto['type'], $allowedTypes)) {
            $errores[] = "Solo se permiten imágenes JPG o PNG";
        } else {
            // Generar nombre único para el archivo
            $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
            $fotoPath = 'uploads/' . uniqid() . '.' . $extension;

            if (!move_uploaded_file($foto['tmp_name'], $fotoPath)) {
                $errores[] = "Error al guardar la imagen";
                $fotoPath = 'fotoproducto/user.png'; // Revertir a valor por defecto
            }
        }
    }

    //--------------------------REGISTRAR USUARIO

    if (empty($errores)) {
        $respuesta = verificar($con, $login, $telefono, $tipo, $nombre, $password, $fotoPath);

        if ($respuesta === 'REGISTRADO') {
            $_SESSION['registro_pendiente'] = [
                'login' => $login,
                'tipo' => $tipo,
                'password' => $password,
                'nombre' => $nombre,
                'telefono' => $telefono,
                'foto' => $fotoPath
            ];
            $mensaje[] = "Registro completado";
            echo mostrarMensajes($mensaje, true);
        } else {
            $errores[] = $respuesta;
            echo mostrarMensajes($errores, false);
        }
        
    }
    else{
        echo mostrarMensajes($errores, false);
    }

}

function verificar($con, $login, $telefono, $tipo, $nombre, $password, $foto)
{
    try {
        $existe = $con->searchUser($login);

        if (!empty($existe)) {
            return "El usuario ya existe";
        } else {
            if ($con->RegisterNewUser($telefono, $login, $tipo, $nombre, $password, $foto)) {
                return "REGISTRADO";
            } else {
                return "Error al registrar el usuario";
            }
        }
    } catch (Exception $e) {
        return "Error al verificar el usuario";
    }
}




function mostrarMensajes($mensajes, $esExito = false)
{
    $mensajeFinal = implode('\n', $mensajes);
    $redireccion = $esExito ? '../Views/LoginView.php' : '../Views/RegistrarUsuarioView.php';

    return '<script>
        alert("' . $mensajeFinal . '");
        window.location.href = "' . $redireccion . '";
    </script>';
}
