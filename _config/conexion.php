<?php
session_start();
	// local
	$servidor = "localhost";
	$cuenta = "root";
	$password = "rootmyapm";
	$database = "clubdeconsumidores";
	$urlapp = 'http://localhost/app-cash-flag';

	// // produccion
	// $servidor = "localhost:3306";
	// $cuenta = "sgcco_club";
	// $password = "club12345**";
	// $database = "sgcconsu_clubdeconsumidores";
	// // pruebas
	// $servidor = "localhost:3306";
	// $cuenta = "sgcconsu_pruebas";
	// $password = "pruebas12345**";
	// $database = "sgcconsu_pruebas";

$link = mysqli_connect($servidor, $cuenta, $password) or die ("Error al conectar al servidor.");
mysqli_select_db($link, $database) or die ("Error al conectar a la base de datos.");
date_default_timezone_set('America/Caracas');
set_time_limit(3600);