<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 09/06/2019
 * Time: 10:21 PM
 */


	include_once("funcionesAdministrativas.php");
//Consulto los permisos a los que tiene acceso el usuario, y en base a esto arma el menu
	session_start();
    if ( ! isset($_SESSION['email']))
    {
        header ("Location: index.html");
        exit;
    }else{
        $email = $_SESSION['email'];
        $nombre = $_SESSION['nombre'];
        $apellidos  = $_SESSION['apellidos'];
        $rol  = $_SESSION['rol'];
        $rolNombre  = $_SESSION['rolNombre'];
        $idUsuario = $_SESSION['id'];
        $imagen =$_SESSION['imagen'];


        include_once("funcionesEditor.php");
        include_once ("funcionesSuperAdmin.php");


        //Consulto los permisos a los que tiene acceso el usuario, y en base a esto arma el menu
        $global_permisos = array();


        $global_permisos = obtenerPermisos($rol);

    }
?>