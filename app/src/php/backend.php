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

if ($_POST) {
    	if (isset($_POST['delete'])) {
            borrar();
    	} elseif (isset($_POST['update'])) {
            actualizar();
        } elseif (isset($_POST["addtxtName"])){
        	agregar();
        }
    }
	function agregar()
	{
	    $cnx = mysqli_connect("127.0.0.1","root","n0m3l0","Agenda") or die("Error en la conexión a MySql");
		//insertar datos
		$nombre=$_POST["addtxtName"];
		$cumpleanios=$_POST["addtxtBirthday"];
		$telefono=$_POST["addtxtPhone"];
		
		$query="insert into Contacto (nombre,cumpleanios,telefono) values ('$nombre','$cumpleanios','$telefono');";
		//echo $query;
		abrirconexion();
		$res = mysqli_query($cnx, $query);
		cerrarconexion();
	}
    function borrar()
    {
       $cnx = mysqli_connect("127.0.0.1","root","n0m3l0","Agenda") or die("Error en la conexión a MySql");
		//borrar datos
       $id=$_POST["id"];
       
       $query="delete from Contacto where id=$id;";
       abrirconexion();
	   $res = mysqli_query($cnx, $query);
	   cerrarconexion();
    }

    function actualizar()
    {
       echo "The actualizar function is called.";
    }
?>