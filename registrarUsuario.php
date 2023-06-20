
<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 01/07/2019
 * Time: 03:01 PM
 */

include("lib/funcionesAdministrativas.php");
$target_dir="uploads/profiles/";
$microtime =microtime(true);//fecha y hora de microsegundos
$target_file = $target_dir . basename($_FILES["profilePhoto"]["name"]);
$uploadOk=1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


if (isset($_POST['email']) && isset($_POST['rol'])) {
    $check = getimagesize($_FILES["profilePhoto"]["tmp_name"]);
    if($check !== false)
        $uploadOk=1;
    else
        $uploadOk=0;

    if(file_exists($target_file))
        $uploadOk=0;

    if($_FILES["profilePhoto"]["size"] >2000000)
        $uploadOk=0;

    if($imageFileType != "jpg" && $imageFileType != "jpeg")
        $uploadOk =0;

    if($uploadOk ==0)
        echo "no se puede subir la imagen de perfil";
    else{

        $newName=$target_dir.$microtime.".jpeg";

        if(move_uploaded_file($_FILES["profilePhoto"]["tmp_name"],$newName))
        {
            $nombre = addslashes($_POST['nombre']);
            $apellidos = addslashes($_POST['apellidos']);
            $email = addslashes($_POST['email']);
            $idrol = $_POST['rol'];
            $idDependencia = $_POST['dependencia'];

            $random=rand(1,10000);
            $password="pass".$random;

            nuevoUsuario($nombre,$apellidos,$email,$password,$idrol,$idDependencia,$newName);
        }else
        {
            echo "Lo sentimos, ocurrio un error subiendo la imagen";
        }
    }



}
?>
