<?php
function mensajes($archivojson,$texto){
    $parametros = json_decode(file_get_contents($archivojson),true);
    $mensaje = '[';
    for ($i = 0; $i < count($parametros["mensajes"][$texto]); $i++) {
        $mensaje .= '"' . $parametros["mensajes"][$texto][$i] . '"';
        if (count($parametros["mensajes"][$texto]) > 1 && $i + 1 < count($parametros["mensajes"][$texto])) {
            $mensaje .= ',';
        }
    }
    $mensaje .= ']';
    return $mensaje;
}

function asignacodigo($ultcupon){
    $valores = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $a = strlen($valores)-1;
    $base = 36;
    $codigo = '';
    $arriba = 1;
    $newcodigo = '';
    $numero = $ultcupon;
    // echo $numero.'<br>';
    for ($i=strlen($ultcupon)-1 ; $i>=0 ; $i--) { 
        $pos = strpos($valores, substr($numero,$i,1));
        if ($arriba==1) {
            if ($pos==$a) {
                $codigo = substr($valores,0,1);
            } else {
                $codigo = substr($valores,$pos+1,1);
                $arriba = 0;
            }
        } else {
            $codigo = substr($numero,$i,1);
        }
        $newcodigo = $codigo.$newcodigo;
    }
    // switch (strlen($newcodigo)) {
    //  case '1':
    //      $newcodigo = '0000'.$newcodigo;
    //      break;
    //  case '2':
    //      $newcodigo = '000'.$newcodigo;
    //      break;
    //  case '3':
    //      $newcodigo = '00'.$newcodigo;
    //      break;
    //  case '4':
    //      $newcodigo = '0'.$newcodigo;
    //      break;
    // }
    for ($i=0 ; $i< strlen($newcodigo); $i++) { 
        // echo substr($newcodigo,$i,1).'<br>';
    }

    return $newcodigo;
}

function asignacodigolargo($ultcupon){
    $newcodigo = $ultcupon;

    $cuponlargo = substr($newcodigo,0,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["email"],-1)));
    $cuponlargo .= substr($newcodigo,2,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["nombres"],-1)));
    $cuponlargo .= substr($newcodigo,4,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["apellidos"],-1)));
    $cuponlargo .= substr($newcodigo,6,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["telefono"],-1)));
    $cuponlargo .= substr($newcodigo,8,2);

    return $cuponlargo;
}

function asignacodigolargo2($ultcupon,$email,$nombres,$apellidos,$telefono){
    $newcodigo = $ultcupon;

    $cuponlargo = substr($newcodigo,0,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($email,-1)));
    $cuponlargo .= substr($newcodigo,2,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($nombres,-1)));
    $cuponlargo .= substr($newcodigo,4,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($apellidos,-1)));
    $cuponlargo .= substr($newcodigo,6,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($telefono,-1)));
    $cuponlargo .= substr($newcodigo,8,2);

    return $cuponlargo;
}

function codigocaracter($valor) {
    $llaves = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $codigos =  '111213141A1B1C1D212223242A2B2C2D3132';
    $codigos .= '33343A3B3C3D414243444A4B4C4DA1A2A3A4';

    $posicion = strpos($llaves, $valor);
    $pos2 = $posicion*2;
    $newvalor = substr($codigos,$pos2,2);

    return $newvalor;
}

function generagiftcard($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link) {
    // Busca el próximo número correlativo (único)
    $query = "select dcg from _parametros";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
        $numgiftcard = $row["dcg"];
    } else {
        $numgiftcard = 0;
    }
    $numgiftcard++;

    // Si el número del correlativo supera los cuatro dígitos se trunca a cuatro
    if ($numgiftcard > 9999) { $numgiftcard = 1; }

    // Rellena con ceros los caracteres faltantes hasta 4
    if ($numgiftcard < 10) {
        $txtgiftcard = "000".trim($numgiftcard);
    } elseif ($numgiftcard < 100) {
        $txtgiftcard = "00".trim($numgiftcard);
    } elseif ($numgiftcard < 1000) {
        $txtgiftcard = "0".trim($numgiftcard);
    } else {
        $txtgiftcard = trim($numgiftcard);
    }
    /*
        El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:

        AABBCCDDEEFFGGGG -> AABB CCDD EEFF GGGG

        AA   = Código de dos dígitos correspondiente a la primera letra del nombre
        BB   = Código de dos dígitos correspondiente a la primera letra del apellido
        CC   = Código de dos dígitos correspondiente al último dígito del teléfono
        DD   = Código de dos dígitos correspondiente a la primera letra del email
        EE   = Código de dos dígitos correspondiente a la primera letra del nombre del proveedor
        FF   = Código de dos dígitos correspondiente a la primera letra de la moneda
        GGGG = Número correlativo de 4 dígitos
    */
    $card = "";
    $card .= generacodigo(substr($nombres,0,1),$link);
    $card .= generacodigo(substr($apellidos,0,1),$link);
    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    $card .= generacodigo(substr($email,0,1),$link);
    $card .= generacodigo(substr($nombreproveedor,0,1),$link);
    $card .= generacodigo(substr($moneda,0,1),$link);
    $card .= substr($txtgiftcard,0,1);
    $card .= substr($txtgiftcard,1,1);
    $card .= substr($txtgiftcard,2,1);
    $card .= substr($txtgiftcard,3,1);

    return $card;
}

function generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link) {
    // Busca el próximo número correlativo (único)
    $query = "select dcp from _parametros";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
        $numprepago = $row["dcp"];
    } else {
        $numprepago = 0;
    }
    $numprepago++;

    // Si el número del correlativo supera los cuatro dígitos se trunca a cuatro
    if ($numprepago > 9999) { $numprepago = 1; }

    // Rellena con ceros los caracteres faltantes hasta 4
    if ($numprepago < 10) {
        $txtprepago = "000".trim($numprepago);
    } elseif ($numprepago < 100) {
        $txtprepago = "00".trim($numprepago);
    } elseif ($numprepago < 1000) {
        $txtprepago = "0".trim($numprepago);
    } else {
        $txtprepago = trim($numprepago);
    }
    /*
        El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:

        AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF

        AA = Código de dos dígitos correspondiente a la primera letra del nombre
        G  = Primer dígito del número correlativo de 4 dígitos
        BB = Código de dos dígitos correspondiente a la primera letra del apellido
        G  = Segundo dígito del número correlativo de 4 dígitos
        CC = Código de dos dígitos correspondiente al último dígito del teléfono
        DD = Código de dos dígitos correspondiente a la primera letra del email
        G  = Tercer dígito del número correlativo de 4 dígitos
        EE = Código de dos dígitos correspondiente a la primera letra del nombre del proveedor
        G  = Cuarto dígito del número correlativo de 4 dígitos
        FF = Código de dos dígitos correspondiente a la primera letra de la moneda
    */
    $card = "";
    $card .= generacodigo(substr($nombres,0,1),$link);
    $card .= substr($txtprepago,0,1);
    $card .= generacodigo(substr($apellidos,0,1),$link);
    $card .= substr($txtprepago,1,1);
    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    $card .= generacodigo(substr($email,0,1),$link);
    $card .= substr($txtprepago,2,1);
    $card .= generacodigo(substr($nombreproveedor,0,1),$link);
    $card .= substr($txtprepago,3,1);
    $card .= generacodigo(substr($moneda,0,1),$link);

    return $card;
}

function generacodigo($letra,$link) {
    $query = "select codigo from _codigo where valor='".$letra."'";
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $codigo = $row["codigo"];
    } else {
        $query = "select codigo from _codigo where valor='?'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $codigo = $row["codigo"];
    }
    return $codigo;
}

// Generar el próximo número de transacción en el pdv
function generatransaccion_pdv($link, $database) {
    // Busca el próximo número correlativo (único)
    $query = "select count(id) as increment from pdv_transacciones";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
            $numero = $row["increment"];
    } else {
            $numero = 0;
    }
    return $numero;
}

function generatarjetaAE($post, $link) {
    // Asignación de variables
    $nombres   = $post['nombres'];
    $apellidos = $post['apellidos'];
    $telefono  = $post['telefono'];
    $email     = $post['email'];

    $moneda = "ae" ;
    $monto = 0.00 ;

    $montobs = 0.00;
    $montodolares = 0.00;
    $montocripto = $monto; 

    $tipotransaccion = '01';
    $tasadolarbs = 1.00;
    $tasadolarcripto = 1.00;
    $idproveedor = 3;  // Cash-Flag
    $tipopago = 'efectivo' ;
    $origen = 'afiliacion' ;
    $referencia = 'nuevosocio' ;

    $nombreproveedor = "Cash-Flag";

    // Buscar el id del socio (si existe)
    $query = "select * from socios where email='".trim($post['email'])."'";
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $idsocio = $row["id"];
    } else {
        $idsocio = 0;
    }

    // Generar numero de tarjeta partiendo de los datos enviados
    $cardnew = generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);
    /*
    El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:
    AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF
    0123456789012345
        x  x    x  x
    */
    $dcp = intval(substr($cardnew,2,1).substr($cardnew,5,1).substr($cardnew,10,1).substr($cardnew,13,1));

    $card = $cardnew;
    $saldoant = 0.00;
    $saldo = ($tipopago == 'efectivo') ? $monto : 0.00 ;

    // Fecha de compra
    $fecha = date('Y-m-d');

    // Fecha de vecnimiento (1 año)
    $fechavencimiento = strtotime('+1 year', strtotime ($fecha));
    $fechavencimiento = date('Y-m-d', $fechavencimiento);

    $datetime1 = date_create($fecha);
    $datetime2 = date_create($fechavencimiento);
    $diferencia = date_diff($datetime1, $datetime2);

    $validez = substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4);

    $status = 'Lista para usar';
    $fechaconfirmacion = $fecha;
    
    // Encripta la card
    $hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

    $query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."')";
    if ($result = mysqli_query($link,$query)) {
		$quer0 = "INSERT INTO cards (card, tipo) VALUES ('".$card."','prepago')";
		if ($resul0 = mysqli_query($link,$quer0)) {
			$query = "INSERT INTO prepago (card, nombres, apellidos, telefono, email, saldo, saldoentransito, moneda, fechacompra, fechavencimiento, validez, status, id_socio, id_proveedor, hash, premium) VALUES ('".$card."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",0.00,'".$moneda."','".$fecha."','".$fechavencimiento."','".$validez."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."',1)";
			if ($result = mysqli_query($link,$query)) {
				// Punto de venta
				$tipo2 = '51'; 
				// Insertar transacción confirmada
				$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, ';
				$quer2 .= 'moneda, monto, instrumento, id_instrumento, documento, status, origen, token) ';
                $quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'", ';
                $quer2 .= '"'.$moneda.'",'.$monto.',"prepago","'.$card.'","'.$referencia.'","'.$status.'", ';
				$quer2 .= '"'.$origen.'","")';
				$resul2 = mysqli_query($link,$quer2);
				$querx = 'UPDATE _parametros SET dcp='.$dcp;
				$resulx = mysqli_query($link,$querx);
            }
        }
    }
}

function generatarjetadolar($post, $link){
    // Asignación de variables
    $nombres   = $post['nombres'];
    $apellidos = $post['apellidos'];
    $telefono  = $post['telefono'];
    $email     = $post['email'];

    $moneda = "dolar" ;
    $monto = 1.00 ;

    $montobs = 0.00;
    $montodolares = $monto;
    $montocripto = 0.00;

    $tipotransaccion = '01';
    $tasadolarbs = 1.00;
    $tasadolarcripto = 1.00;
    $idproveedor = 3;  // Cash-Flag
    $tipopago = 'efectivo' ;
    $origen = 'afiliacion' ;
    $referencia = 'nuevosocio' ;

    $nombreproveedor = "Cash-Flag";

    // Buscar el id del socio (si existe)
    $query = "select * from socios where email='".trim($post['email'])."'";
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $idsocio = $row["id"];
    } else {
        $idsocio = 0;
    }

    // Generar numero de tarjeta partiendo de los datos enviados
    $cardnew = generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);
    /*
    El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:
    AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF
    0123456789012345
        x  x    x  x
    */
    $dcp = intval(substr($cardnew,2,1).substr($cardnew,5,1).substr($cardnew,10,1).substr($cardnew,13,1));

    $card = $cardnew;
    $saldoant = 0.00;
    $saldo = ($tipopago == 'efectivo') ? $monto : 0.00 ;

    // Fecha de compra
    $fecha = date('Y-m-d');

    // Fecha de vecnimiento (1 año)
    $fechavencimiento = strtotime('+1 year', strtotime ($fecha));
    $fechavencimiento = date('Y-m-d', $fechavencimiento);

    $datetime1 = date_create($fecha);
    $datetime2 = date_create($fechavencimiento);
    $diferencia = date_diff($datetime1, $datetime2);

    $validez = substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4);

    $status = 'Lista para usar';
    $fechaconfirmacion = $fecha;
    
    // Encripta la card
    $hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

    $query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."')";
    if ($result = mysqli_query($link,$query)) {
		$quer0 = "INSERT INTO cards (card, tipo) VALUES ('".$card."','prepago')";
		if ($resul0 = mysqli_query($link,$quer0)) {
			$query = "INSERT INTO prepago (card, nombres, apellidos, telefono, email, saldo, saldoentransito, moneda, fechacompra, fechavencimiento, validez, status, id_socio, id_proveedor, hash, premium) VALUES ('".$card."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",0.00,'".$moneda."','".$fecha."','".$fechavencimiento."','".$validez."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."',1)";
			if ($result = mysqli_query($link,$query)) {
				// Punto de venta
				$tipo2 = '51'; 
				// Insertar transacción confirmada
				$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, ';
				$quer2 .= 'moneda, monto, instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
                $quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'", ';
                $quer2 .= '"'.$moneda.'",'.$monto.',"prepago","'.$card.'","'.$referencia.'","'.$status.'", ';
				$quer2 .= '"'.$origen.'","",0,"")';
				$resul2 = mysqli_query($link,$quer2);
				$querx = 'UPDATE _parametros SET dcp='.$dcp;
				$resulx = mysqli_query($link,$querx);
            }
        }
    }
}

function enviasms($telefono,$mensaje){
    //parámetros de envío
    $usuario="sgcvzla@gmail.com";
    $clave="Ma24032008.";

    $parametros="usuario=$usuario&clave=$clave&texto=$mensaje&telefonos=$telefono";

    $url = "http://www.sistema.massivamovil.com/webservices/SendSms";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST,true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
    $response = curl_exec($ch);

    curl_close($ch);
}

function mensajebienvenida($reg) {
	$correo = $reg["email"];

	$mensaje = utf8_decode('Hola '.trim($reg["nombres"]).',<br/><br/>');
	$mensaje .= utf8_decode('¡Gracias por querer formar parte de nuestra comunidad!<br/><br/>');

	$mensaje .= utf8_decode('Queremos conocerte un poco más y ofrecerte premios, promociones o productos/servicios especialmente diseñados para ti, pero necesitamos que nos brindes alguna información que nos ayudará a prestarte un mejor servicio, innovar en nuestros premios y hacerte la vida mucho más fácil y gratificante, además desde ya comenzaras a ganar, luego de completar <a href="https://app.cash-flag.com/registro/index.html?idp='.$reg["idproveedor"].'&ids='.$reg["id"].'">este formulario</a> recibirás un premio de bienvenida.<br/><br/>');

	$mensaje .= utf8_decode('<b>Te garantizamos que tu información será guardada celosamente y nunca será compartida con ningún tercero sin tu consentimiento y te aseguramos que siempre cumpliremos con las Leyes vigentes en lo relacionado al tratamiento de tus datos personales.</b><br/><br/>');

	$mensaje .= utf8_decode('Nuestra comunidad está en permanente evolución y tú como un miembro muy importante puedes aportarnos ideas o sugerencias que la harán crecer, ten la certeza que serás escuchado(a) y tus sugerencias o comentarios serán repondidos en un lapso de tiempo razonable con mucho entusiasmo por resolver tus inquietudes, para nosotros será un placer atenderte por medio del email: <a href="mailto:info@cash-flag.com">info@cash-flag.com</a>.<br/><br/>');

	$mensaje .= utf8_decode('Bienvenido!!!'.'<br/><br/>');
	$mensaje .= utf8_decode('Cash-Flag'.'<br/><br/>');

	$mensaje .= utf8_decode('<b>Nota:</b> Esta cuenta no es monitoreada, por favor no respondas este email, si deseas comunicarte con tu club escribe a: <b><a href="mailto:info@cash-flag.com">info@cash-flag.com</a></b>'.'<br/><br/>');

	$asunto = utf8_decode(trim($reg["nombres"]).', Bienvenido a Cash-Flag, tu comunidad de beneficios!!!');
	$cabeceras = 'Content-type: text/html;';
	// if ($_SERVER["HTTP_HOST"]!='localhost') {
		mail($correo,$asunto,$mensaje,$cabeceras);
	// }
}

function recargapremiumdolar($link,$idsocio,$email,$telefono,$nombres,$apellidos) {
	$query = "select * from prepago where nombres='".trim($nombres)."' and apellidos='".trim($apellidos)."' and telefono='".trim($telefono)."' and email='".trim($email)."' and id_proveedor=3 and moneda='dolar'";
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		$tarjetaexiste = true;
		$card = $row["card"];
		$saldoant = $row["saldo"];

		$fecha = date("Y-m-d");
		$tipotransaccion = '01';
		$tasadolarbs = 1.00;
		$tasadolarcripto = 1.00;
		
		$query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$idsocio.",3,'".$fecha."','".$tipotransaccion."','dolar',0 ,1 ,0 ,".$tasadolarbs.",".$tasadolarcripto.",'0000','registro','Confirmada','".$card."')";
		if ($result = mysqli_query($link,$query)) {
			$saldo = $saldoant + 1;
			$query = "UPDATE prepago SET saldo=".$saldo." WHERE card='".trim($card)."'";
			if ($result = mysqli_query($link,$query)) {
				$respuesta = '{"exito":"SI","mensaje":"Transacción exitosa."}';	
			} else {
				$respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';
			}
		} else {
			$respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';
		}
	} else {
		$respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';
	}
}

?>