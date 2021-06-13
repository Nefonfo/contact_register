<?php

	ini_set('display_errors', E_ALL);


function abrirconexion()
{
    //Conectar a la BD
	$cnx = mysqli_connect("127.0.0.1","root","n0m3l0","Agenda") or die("Error en la conexión a MySql");
	//Comprueba la conexión
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

}
function cerrarconexion()
{
    $cnx = mysqli_connect("127.0.0.1","root","n0m3l0","Agenda") or die("Error en la conexión a MySql");
    //cierro la conexion
    mysqli_close($cnx);
}

?>