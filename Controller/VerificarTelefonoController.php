<?php
require('../Model/Conexion.php');

function validarNumero($x){
    $URL="http://apilayer.net/api/validate";
    $access_key="a6df017f3f16d30cdde0ec268fe259ec";
    $country_code="MX";
    $format=1;

    $consulta=http_build_query([
        'access_key'=>$access_key,
        'number'=>$x,
        'country_code'=>$country_code,
        'format'=>$format]);

    $newUrl=$URL."?".$consulta;    
    $consumo=file_get_contents($newUrl);
    $data = json_decode($consumo, true);

    if ($consumo === false) {
        return false;
    } else {
        return $data['valid'] ?? false;
    }
}

if (isset($_GET['telefono'])) {
    $telefono = $_GET['telefono'];
    $esValido = validarNumero($telefono);
    
    header('Content-Type: application/json');
    echo json_encode(['valid' => $esValido]);
    exit;
}

echo json_encode(['valid' => false]);