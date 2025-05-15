<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://169.254.169.254/metadata/instance?api-version=2021-02-01");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Metadata: true"));
$response = curl_exec($ch);

if (!$response) {
    die("❌ NO se pudo acceder al servicio de metadata: " . curl_error($ch));
}
curl_close($ch);

echo "✅ Servicio de metadata accedido correctamente:<br><br>";
echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";





/* direcciones de salida 
4.174.179.154, 4.174.179.155, 4.174.179.163, 4.174.179.176, 4.174.179.177, 
4.174.179.190, 4.174.177.167, 4.174.178.0, 4.174.178.30, 4.174.178.51, 4.174.176.158,
4.174.179.73, 4.174.179.215, 4.174.180.96, 4.174.180.179, 4.174.176.246, 4.174.176.247,


  4.174.177.28, 4.174.177.29, 4.174.178.192, 4.174.178.193, 4.174.179.104, 4.174.179.105,
   4.174.179.117, 4.174.179.191, 4.174.179.203, 4.174.179.212, 4.174.179.216, 4.174.179.220, 4.174.179.221, 20.48.204.1
 */


?>



