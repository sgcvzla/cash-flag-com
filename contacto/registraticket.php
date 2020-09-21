<?php 
// session_start();
header('Content-Type: application/json');
include_once("../config/conexion.php");

$imp = $_POST["impacto"];
$tip = $_POST["tipo"];
$destip = '';
$severidad = 3;
switch ($tip) {
	case 'comen': 
		$destip = 'Comentario';
		switch ($imp) {
			case 'alto': $severidad = 3; break;
			case 'bajo': $severidad = 3; break;
			case 'medio': $severidad = 3; break;
			default: $severidad = 3; break;
		}
		break;
	case 'falla': 
		$destip = 'Reporte de falla';
		switch ($imp) {
			case 'alto': $severidad = 1; break;
			case 'bajo': $severidad = 2; break;
			case 'medio': $severidad = 1; break;
			default: $severidad = 3; break;
		}
		break;
	case 'mejor': 
		$destip = 'Oportunidad de mejora';
		switch ($imp) {
			case 'alto': $severidad = 2; break;
			case 'bajo': $severidad = 3; break;
			case 'medio': $severidad = 3; break;
			default: $severidad = 3; break;
		}
		break;
	case 'nuevo': 
		$destip = 'Nuevo requerimiento';
		switch ($imp) {
			case 'alto': $severidad = 1; break;
			case 'bajo': $severidad = 3; break;
			case 'medio': $severidad = 2; break;
			default: $severidad = 3; break;
		}
		break;
	default: 
		$severidad = 3;
		break;
}
$fecha = date('Y-m-d H:i:s');
$respuesta = '';

// $query = "SELECT * from parametros";
// $result = mysqli_query($link,$query);
// if ($row = mysqli_fetch_array($result)) {
// 	$correo = $row["email"];
	$correo = 'sgc-consultores-190075-blur-one@leads.getsirena.com';
// }

// $query = "INSERT INTO tickets (cliente, sistema, modulo, tipo, detalles, impacto, nombre, email, telefono, fechaticket,severidad, status) VALUES ('".$_POST["cliente"]."','".$_POST["sistema"]."','".$_POST["modulo"]."','".$_POST["tipo"]."','".$_POST["detalles"]."','".$_POST["impacto"]."','".$_POST["nombre"]."','".$_POST["email"]."','".$_POST["telefono"]."','".$fecha."',".$severidad.",'Pendiente')";
// $result = mysqli_query($link,$query);
/*
$query = "SELECT ticket from tickets where cliente='".$_POST["cliente"]."' and sistema='".$_POST["sistema"]."' and modulo='".$_POST["modulo"]."' and nombre='".$_POST["nombre"]."' and fechaticket='".$fecha."'";
$result = mysqli_query($link,$query);
if ($row = mysqli_fetch_array($result)) {
	$ticket = $row["ticket"];
	$mensaje = utf8_decode('Buen día,<br/><br/>');
	$mensaje .= 'Se ha generado el siguiente ticket de soporte:<br/><br/>';
	$mensaje .= '<b>Ticket: </b>'.$ticket.'<br/>';

	$mensaje .= '<b>Cliente: </b>'.utf8_decode($_POST["cliente"]).'<br/>';
	$mensaje .= '<b>Sistema: </b>'.utf8_decode($_POST["sistema"]).'<br/>';
	$mensaje .= utf8_decode('<b>Módulo: </b>'.$_POST["modulo"].'<br/>');

	$mensaje .= '<b>Tipo de solicitud: </b>'.$destip.'<br/>';
	$mensaje .= '<b>Detalles: </b>'.utf8_decode($_POST["detalles"]).'<br/>';
	$mensaje .= '<b>Impacto sobre el negocio: </b>'.$_POST["impacto"].'<br/>';
	$mensaje .= utf8_decode('<b>Reportó: </b>'.$_POST["nombre"].'<br/>');
	$mensaje .= '<b>email: </b><a href="mailto:'.$_POST["email"].'?subject=Respuesta al ticket No. '.$ticket.'">'.$_POST["email"].'</a><br/>';
	$mensaje .= utf8_decode('<b>Teléfono: </b>'.$_POST["telefono"].'<br/>');
	$desfecha = substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4).' a las '.substr($fecha,11,5).' horas.';
	$mensaje .= utf8_decode('<b>Fecha y hora en que se reportó: </b>'.$desfecha.'<br/>');
	$mensaje .= '<b>Severidad asignada por el sistema: </b>'.$severidad.'<br/><br/>';

	// if (strpos($_SERVER["SERVER_NAME"],'localhost')===FALSE) {	           	
	// 	$mensaje .= '<a href="localhost/sgcnew/soporte/respuesta.html?ticket='.$ticket.'">Responder al ticket</a><br/><br/>';
	// } else {
		$mensaje .= '<a href="https://www.sgc-consultores.com.ve/soporte/respuesta.html?ticket='.$ticket.'">Responder al ticket</a><br/><br/>';
	// }

	$asunto = "Ticket de soporte No.: ".$ticket." (Contacto de prospecto o cliente nuevo)";
	$cabeceras = 'Content-type: text/html;';
	if (strpos($_SERVER["SERVER_NAME"],'localhost')===FALSE) {	           	
		mail($correo,$asunto,$mensaje,$cabeceras);
	}

	$query = "INSERT INTO historial (ticket, detalles, fechastatus) VALUES (".$ticket.",'Apertura: ".$_POST["detalles"]."','".$fecha."')";
	$result = mysqli_query($link,$query);

	$respuesta = '{"exito":"SI","ticket":'.$row["ticket"].'}';
} else {
	$respuesta = '{"exito":"NO","ticket":0}';
}
*/
$mensaje  = "Nombre Completo: ".utf8_decode($_POST["cliente"])."\n";
// $mensaje .= "Nombre: Luis 8\n";
// $mensaje .= "Apellido: Rodríguez 8\n";
$mensaje .= "Email: ".trim($_POST["email"])."\n";
$mensaje .= "Teléfono: ".trim($_POST["telefono"])."\n";
$mensaje .= "Origen: Web-Cash-Flag\n";
$mensaje .= "Industria: retail\n";
$mensaje .= "Comentario: ".utf8_decode($_POST["detalles"])."\n";

$asunto = "Sirena - Email Estándar";
// $cabeceras = 'Content-type: text/html;';
$cabeceras = "";
mail($correo,$asunto,$mensaje,$cabeceras);

// $respuesta = '{"exito":"SI","ticket":'.$row["ticket"].'}';
$respuesta = '{"exito":"SI","ticket":1}';
echo $respuesta;
?>
