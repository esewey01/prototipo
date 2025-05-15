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
?>
