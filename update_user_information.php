<?php

include "utils/constants.php";
									      include "utils/database_utils.php";

if(isset($_POST["updateUserData"])){
									      $params = array();
									      if(isset($_POST["new_user_dependency"])){
									          $params["ID_OPCION_DEPENDENCIA"] = $_POST["new_user_dependency"];
									      }
									      if(strlen($_POST["new_user_name"]) > 0){
									          $params["NOMBRE"] = $_POST["new_user_name"];
									      }
									      if(strlen($_POST["new_user_lastname"]) > 0){
									          $params["APELLIDOS"] = $_POST["new_user_lastname"];
									      }
									      if(strlen($_POST["new_user_password"]) > 0){
									          $params["CONTRASENA"] = md5($_POST["new_user_password"]);
									      }
									      if(strlen($_POST["new_user_email"]) > 0){
									          $params["CORREO"] = $_POST["new_user_email"];
									      }
									      if(sizeof($params) > 0){
									          //Guardar
									          //Para guardarlo necesitas su Key ID... ¿?
									          $updatedUserId = $_POST["user_id"];
									          
									          foreach ($params as $clave => $valor)
									            $update = simpleUpdateRegister($TABLA_USUARIOS, $clave, $valor, "ID",  $updatedUserId);
									          
									          
									          
									          $redirect = "main.php?mr=31&target=".$updatedUserId;
									          header('Location: '.$redirect);
									      }
									      
									  }
									  
?>