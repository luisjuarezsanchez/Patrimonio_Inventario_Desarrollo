<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 01/07/2019
 * Time: 12:40 PM
 */

include_once "conexion.php";
include_once "funcionesGenerales.php";


function obtenerUsuarios()
{
    $con = conecta_DB();
    $sql= "SELECT usuarios.ID, usuarios.NOMBRE, usuarios.ID_ESTADO_USUARIO,usuarios.APELLIDOS, usuarios.CORREO, cat_estado_usuario.NOMBRE as ESTADO, cat_roles.NOMBRE as ROL, cat_opciones.NOMBRE as DEPENDENCIA
          FROM usuarios
          INNER JOIN cat_estado_usuario ON usuarios.ID_ESTADO_USUARIO = cat_estado_usuario.ID 
          INNER JOIN cat_roles ON usuarios.ID_ROL = cat_roles.ID
          INNER JOIN cat_opciones ON usuarios.ID_OPCION_DEPENDENCIA = cat_opciones.ID ";
    $result = mysqli_query($con,$sql);
    mysqli_close($con);

    $usuarios = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){


        $object = (object) [
            'id' => codifica($row['ID']),
            'estatus' => codifica($row['ESTADO']),
            'estatusNum'=>codifica($row['ID_ESTADO_USUARIO']),
            'nombre' => codifica($row['NOMBRE']),
            'apellidos' => codifica( $row['APELLIDOS']),
            'email' => codifica($row['CORREO']),
            'rol'=> codifica( $row['ROL']),
            'dependencia'=> codifica($row['DEPENDENCIA']),
        ];
        $usuarios[] = $object;
    }

    return $usuarios;
}

?>