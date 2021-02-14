<?php
header('Content-Type: application/json');
// include_once("./cash-flag-email.php");
include_once("../_config/conexion.php");
include_once("./funciones.php");

$query = "INSERT INTO socios (email, status, telefono, nombres, apellidos, idproveedor, fechanacimiento, sexo, pais, estado, ciudad, nombre_pais, nombre_estado, nombre_ciudad, sector, direccion, donde_entregar, direccion_entrega, edocivil, nombrepareja, cumplepareja, aniversario, padre, nombrepadre, cumplepadre, madre, nombremadre, cumplemadre, hijos, menores5, menores10, menores20, mayores, otrotelef, vehiculo, cedula, rif, profesion, ocupacion, nombretrabajo, direcciontrabajo, emailtrabajo, telefonotrabajo, fecha_afiliacion, registro, secretkey, account) VALUES ('".$_POST["email"]."', 'Activo', '".$_POST["telefono"]."', '".$_POST["nombres"]."', '".$_POST["apellidos"]."',".$_POST["idproveedor"].", '0000-00-00', '', 192, 0, 0, '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', 0, '', '0000-00-00', 0, '', '0000-00-00', 0, 0, 0, 0, 0, '', 0, '', '', '', '', '', '', '', '', '".date("Y-m-d")."', 'Pendiente', '".$_POST["secretkey"]."', '".$_POST["account"]."')";
if($result = mysqli_query($link, $query)) {
    $query = "select * from socios where email='".$_POST['email']."'";
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $id=$row["id"];
        $socio = 1;
        $archivojson = "../registro/registro.json";
        mensajebienvenida($row);
        generatarjetaAE($_POST, $link);
        generatarjetadolar($_POST, $link);
        cupondebienvenida($link,1,$_POST["email"],$_POST["telefono"],$_POST["nombres"],$_POST["apellidos"],$archivojson,$_POST["idproveedor"],$id,$idcomercio);
	    $respuesta = '{"exito":"SI",';
        $respuesta .= '"mensaje":"Registro exitoso"}';
    } else {
        $respuesta = '{"exito":"NO",';
        $respuesta .= '"mensaje":"Falló el registro"}';
    }
} else {
	$respuesta = '{"exito":"NO",';
    $respuesta .= '"mensaje":"Falló el registro"}';
}
echo $respuesta;
?>