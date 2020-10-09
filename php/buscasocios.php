<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$quer0 = 'SELECT * FROM socios where email="'.$_GET["email"].'"';
$resul0 = mysqli_query($link, $quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
    $respuesta = '{"exito":"SI",';
    $respuesta .= '"nombres":"'. trim($ro0["nombres"]) .'",';
    $respuesta .= '"apellidos":"' . trim($ro0["apellidos"]) . '",';
    $respuesta .= '"telefono":"' . $ro0["telefono"] . '",';
    $respuesta .= '"secretkey":"' . $ro0["secretkey"] . '",';
    $respuesta .= '"account":"' . $ro0["account"] . '"';
    $respuesta .= '}';
} else {
    $respuesta = '{"exito":"NO",';
    $respuesta .= '"nombres":"",';
    $respuesta .= '"apellidos":"",';
    $respuesta .= '"telefono":"",';
    $respuesta .= '"secretkey":"",';
    $respuesta .= '"account":""';
    $respuesta .= '}';
}
echo $respuesta;
?>
