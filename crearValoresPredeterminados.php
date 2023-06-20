<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 11/06/2019
 * Time: 02:12 PM
 */
/*
include("lib/funcionesValoresPredeterminados.php");
include ("lib/funcionesAdministrativas.php");
*/
/*

 // roles de usuario
    nuevoRol("Editor");
    nuevoRol("Administrador");
    nuevoRol("Super Administrador");

    //Permisos de usuario
    nuevoPermiso("Consultar fichas disponibles");

    //para los editores
    nuevoPermiso("Ver registros de fichas propios");
    nuevoPermiso("Crear registros de fichas");
    nuevoPermiso("Editar registros de fichas");

    //para administrador
    nuevoPermiso("Rechazar registros de fichas");
    nuevoPermiso("Eliminar registros de fichas");
    nuevoPermiso("Crear nuevos usuarios");
    nuevoPermiso("Crear nuevos campos");
    nuevoPermiso("Crear nuevos catalagos");

    //para super administrador
    nuevoPermiso("Validar usuarios nuevos");
    nuevoPermiso("Cambio de estado de usuarios");
    nuevoPermiso("Eliminar registros de forma permanente");
    nuevoPermiso("Validar regsitros de fichas");


 //Relaciones permisos roles
    nuevaRelacionRolPermiso(1,1);
    nuevaRelacionRolPermiso(1,2);
    nuevaRelacionRolPermiso(1,3);
    nuevaRelacionRolPermiso(1,4);
    nuevaRelacionRolPermiso(2,1);
    nuevaRelacionRolPermiso(2,5);
    nuevaRelacionRolPermiso(2,6);
    nuevaRelacionRolPermiso(2,7);
    nuevaRelacionRolPermiso(2,13);
    nuevaRelacionRolPermiso(3,1);
    nuevaRelacionRolPermiso(3,2);
    nuevaRelacionRolPermiso(3,3);
    nuevaRelacionRolPermiso(3,5);
    nuevaRelacionRolPermiso(3,6);
    nuevaRelacionRolPermiso(3,7);
    nuevaRelacionRolPermiso(3,8);
    nuevaRelacionRolPermiso(3,9);
    nuevaRelacionRolPermiso(3,10);
    nuevaRelacionRolPermiso(3,11);
    nuevaRelacionRolPermiso(3,12);
    nuevaRelacionRolPermiso(3,13);




 //Registro de los 3 tipos de campos que se veran en las fichas
    nuevoTipoCampo("Texto corto",20);
    nuevoTipoCampo("Texto largo",300);
    nuevoTipoCampo("Catalago",80);


    //tipos la papelera
    nuevoTipoRegistroPapelera("Registro");


 //estados de la papelera
    nuevoEstadoPapelera("Activo");
    nuevoEstadoPapelera("Borrado");

    //estados de los usuarios
    nuevoEstadoUsuario("Registrado");
    nuevoEstadoUsuario("Validado");



    nuevoTipoPeriodo("Colección");
    nuevoTipoPeriodo("Colecciones específicas");

    nuevoPeriodo("Prehistórico",1);
    nuevoPeriodo("Arqueológico",1);
    nuevoPeriodo("Virreinato",1);
    nuevoPeriodo("Arte Moderno y Contemporáneo",1);
    nuevoPeriodo("Culturas populares",2);
    nuevoPeriodo("Numismática",2);
    nuevoPeriodo("Historia natural",2);
    nuevoPeriodo("Objeto histórico",1);


    nuevaFicha("Prehistórico",1);
    nuevaFicha("Arte ruprestre y Petroglifos",1);
    nuevaFicha("Vasijas",2);
    nuevaFicha("Esculturas y Figurillas",2);
    nuevaFicha("Objetos en General",2);
    nuevaFicha("Virreinato",3);
    nuevaFicha("Arte Moderno y Contemporáneo",4);
    nuevaFicha("Culturas Populares",5);
    nuevaFicha("Numismática",6);
    nuevaFicha("Rocas y Minerales",7);
    nuevaFicha("Osteología",7);
    nuevaFicha("Taxidermia",7);
    nuevaFicha("Paleontología",7);
    nuevaFicha("Objeto histórico",8);


    nuevoEstadoSeccion("Aprovado");
    nuevoEstadoSeccion("Revisión");


    nuevaSeccion("Campos obligatorios",3,0);
    nuevaSeccion("Datos de identificación general",3,0);
    nuevaSeccion("Tipo de objeto",3,0);
    nuevaSeccion("Forma",3,0);
    nuevaSeccion("Medidas",3,0);
    nuevaSeccion("Nombre sección pendiente",3,0);
    nuevaSeccion("Procedencia",3,0);
    nuevaSeccion("Historia de la pieza y conservación",3,1);
    nuevaSeccion("Registro",3,0);

    nuevaSeccion("Campos obligatorios",4,0);
    nuevaSeccion("Datos de identificación general",4,1);
    nuevaSeccion("Tipo de objeto",4,0);
    nuevaSeccion("Medidas",4,0);
    nuevaSeccion("Nombre sección pendiente",4,0);
    nuevaSeccion("Procedencia",4,0);
    nuevaSeccion("Historia de la pieza y conservación",4,1);
    nuevaSeccion("Registro",4,0);

    nuevaSeccion("Campos obligatorios",5,0);
    nuevaSeccion("Datos de identificación general",5,0);
    nuevaSeccion("Tipo de objeto",5,0);
    nuevaSeccion("Medidas",5,0);
    nuevaSeccion("Nombre sección pendiente",5,0);
    nuevaSeccion("Procedencia",5,0);
    nuevaSeccion("Historia de la pieza y conservación",5,1);
    nuevaSeccion("Registro",5,0);



    nuevaSeccion("Campos obligatorios",1,0);
    nuevaSeccion("Datos generales",1,0);
    nuevaSeccion("Medidas",1,0);
    nuevaSeccion("Localización actual",1,0);
    nuevaSeccion("Observaciones",1,1);
    nuevaSeccion("Registro",1,0);

    nuevaSeccion("Campos obligatorios",2,0);
    nuevaSeccion("Datos de identificación general",2,0);
    nuevaSeccion("Georeferenciación",2,0);
    nuevaSeccion("Historia de la pieza y conservación",2,1);
    nuevaSeccion("Registro",2,0);

    nuevaSeccion("Campos obligatorios",6,0);
    nuevaSeccion("Datos de identificación general",6,0);
    nuevaSeccion("Medidas- Pintura de caballete, grabado",6,0);
    nuevaSeccion("Medidas- Escultura, ornamento religioso, vestuario, mueble",6,0);
    nuevaSeccion("Tipo de obra",6,0);
    nuevaSeccion("Procedencia",6,0);
    nuevaSeccion("Estado físico",6,0);
    nuevaSeccion("Intervenciones anteriores",6,0);
    nuevaSeccion("Historia de la pieza y conservación",6,1);
    nuevaSeccion("Registro",6,0);

    nuevaSeccion("Campos obligatorios",7,0);
    nuevaSeccion("Datos de identificación general",7,0);
    nuevaSeccion("Datos técnicos",7,0);
    nuevaSeccion("Medidas",7,0);
    nuevaSeccion("Medidas Base/Marco",7,0);
    nuevaSeccion("Tema o representación tipológica",7,0);
    nuevaSeccion("Ubicación cronotopológica",7,0);
    nuevaSeccion("Procedencia",7,0);
    nuevaSeccion("Localización actual",7,0);
    nuevaSeccion("Estado de conservación",7,0);
    nuevaSeccion("Restricciones de movilidad",7,0);
    nuevaSeccion("Observaciones",7,1);
    nuevaSeccion("Registro",7,0);

    nuevaSeccion("Campos obligatorios",8,0);
    nuevaSeccion("Ficha básica",8,0);
    nuevaSeccion("Tipo de objeto",8,0);
    nuevaSeccion("Medidas",8,0);
    nuevaSeccion("Ubicación de la pieza",8,0);
    nuevaSeccion("Procedencia",8,0);
    nuevaSeccion("Historia de la pieza y conservación",8,1);
    nuevaSeccion("Registro",8,0);


    nuevaSeccion("Campos obligatorios",9,0);
    nuevaSeccion("Identificación",9,0);
    nuevaSeccion("Tipo de objeto",9,0);
    nuevaSeccion("Descripción",9,0);
    nuevaSeccion("Medidas",9,0);
    nuevaSeccion("Ubicación",9,0);
    nuevaSeccion("Historial",9,1);
    nuevaSeccion("Registro",9,0);

    nuevaSeccion("Campos obligatorios",10,0);
    nuevaSeccion("Identificación",10,0);
    nuevaSeccion("Tipo de objeto",10,0);
    nuevaSeccion("Propiedades físicas",10,0);
    nuevaSeccion("Medidas",10,0);
    nuevaSeccion("Procedencia",10,0);
    nuevaSeccion("Localización actual",10,0);
    nuevaSeccion("Observaciones",10,1);
    nuevaSeccion("Registro",10,0);

    nuevaSeccion("Campos obligatorios",11,0);
    nuevaSeccion("Identificación",11,0);
    nuevaSeccion("Tipo de objeto",11,0);
    nuevaSeccion("Descripción",11,0);
    nuevaSeccion("Medidas",11,0);
    nuevaSeccion("Localización actual",11,0);
    nuevaSeccion("Observaciones",11,1);
    nuevaSeccion("Registro",11,0);

    nuevaSeccion("Campos obligatorios",12,0);
    nuevaSeccion("Identificación",12,0);
    nuevaSeccion("Tipo de objeto",12,0);
    nuevaSeccion("Descripción",12,0);
    nuevaSeccion("Medidas",12,0);
    nuevaSeccion("Localización actual",12,0);
    nuevaSeccion("Observaciones",12,1);
    nuevaSeccion("Registro",12,1);

    nuevaSeccion("Campos obligatorios",13,0);
    nuevaSeccion("Identificación",13,0);
    nuevaSeccion("Tipo de objeto",13,0);
    nuevaSeccion("Descripción",13,0);
    nuevaSeccion("Medidas",13,0);
    nuevaSeccion("Localización actual",13,0);
    nuevaSeccion("Observaciones",13,1);
    nuevaSeccion("Registro",13,0);

    nuevaSeccion("Campos obligatorios", 14,0);
    nuevaSeccion("Identificación",14,0);
    nuevaSeccion("Función del objeto",14,0);
    nuevaSeccion("Características del objeto",14,0);
    nuevaSeccion("Medidas",14,0);
    nuevaSeccion("Localización",14,1);
    nuevaSeccion("Registro",14,0);



    nuevoEstadoRegistro("Guardado");
    nuevoEstadoRegistro("Revisión");
    nuevoEstadoRegistro("Validado");
    nuevoEstadoRegistro("Publicado");

    echo "DATOS CARGADOS";

*/

$myfile = fopen("campos_patrimonio3.txt","r") or die("No se encuentra el archivo");
while(!feof($myfile)){
    
echo "qwdwdq <br>";
/*
    $porciones = explode("-", codifica(fgets($myfile)));

     if(strcmp('FIELD',$porciones[0])==0) {
            list($tipo, $nombre, $nota, $obligatorio) = explode(":", $porciones[1]);
            $idCampo = nuevoCampo($nombre,$nota,$tipo,$obligatorio);
           
            echo $porciones[1] . "<br>";

            $porcionesLink = explode("-", codifica(fgets($myfile)));
            $arraySecciones = array();
            if(strcmp('LINK',$porcionesLink[0])==0) {
                $porcionesSecciones = explode(":", $porcionesLink[1]);
                foreach ($porcionesSecciones as $porcionSeccion)
                {
                    $arraySecciones[]=$porcionSeccion;
                    nuevaSeccionCampo($porcionSeccion,$idCampo,0);
                    echo $porcionSeccion;
                }
                echo "<br>";
            }

            $arrayOpciones = array();
            if($tipo==3)//en caso de ser un catalago
            {
                $opcion=codifica(fgets($myfile));
                while (!feof($myfile))
                {
                    $opcion=trim(codifica(fgets($myfile)));
                    if(strcmp('ENDOPTIONS',$opcion)!=0 ) {
                        $arrayOpciones[] = $opcion;
                        nuevaOpcion($opcion,$idCampo,0);
                        echo $opcion . "<br>";
                    }else
                        break;
                }

            }
            echo "<br><br>";
        }*/
        
}

fclose($myfile);

/*

    nuevoUsuario("Vania","Ramírez","proyectosdigitales.cultura@gmail.com","cultura1234",1,1,"uploads/profiles/admin.jpg");
    autorizarUsuario("proyectosdigitales.cultura@gmail.com");
    nuevoUsuario("Alfredo","del Mazo","alfredomazo@patrimonio.com.mx","password",3,1,"uploads/profiles/Alfredo.jpeg");
    autorizarUsuario("alfredomazo@patrimonio.com.mx");
    nuevoUsuario("Marcela","Gónzalez","marcelaglz@patrimonio.com.mx","password",2,1,"uploads/profiles/marcela.jpeg");
    autorizarUsuario("marcelaglz@patrimonio.com.mx");
    nuevoUsuario("Ivett","Tinoco","ivettinoco@patrimonio.com.mx","password",1,1,"uploads/profiles/Ivett.jpeg");
    autorizarUsuario("ivettinoco@patrimonio.com.mx");
 nuevoUsuario("Alfonso","Sandoval","alfonsosandoval@patrimonio.com.mx","password",3,1,"DEFAULT");
    autorizarUsuario("alfonsosandoval@patrimonio.com.mx");
*/

/*
$cont=1;
while($cont<=178){

            nuevoCampo("Otro","adicional",1,0);
            $cont++;
}
*/


/*
function obtenerNombreCampo($id)
{
    $con = conecta_DB();
    $sql= "SELECT NOMBRE FROM campos where ID = ".$id;
    $result = mysqli_query($con,$sql);
    mysqli_close($con);
    $object=null;
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $object = (object) [
            'nombre' => codifica($row['NOMBRE']),
        ];
    }
    return $object;
}

function cambiarNombre($nuevoNombre,$id)
{
    $con = conecta_DB();
    $sql= "UPDATE campos SET NOMBRE = '".decodifica($nuevoNombre)."' where ID = ".$id;
    $result = mysqli_query($con,$sql);
    mysqli_close($con);
    return true;
}


    $contadorCampoOtro=480;

$myfile = fopen("campos_otros.txt","r") or die("No se encuentra el archivo");
while(!feof($myfile)){
    $idCampo= codifica(fgets($myfile));
    $idOpcion= codifica(fgets($myfile));
    $seccion = codifica(fgets($myfile));
    $indiceOrigen =codifica(fgets($myfile));
    $ficha = codifica(fgets($myfile));
    
     $indiceOtro= $indiceOrigen+1;
     
     nuevaSeccionCampo($seccion,$contadorCampoOtro,$indiceOtro);
     nuevaOpcionCampo($ficha,$idOpcion,$idCampo,$contadorCampoOtro);
    $contadorCampoOtro= $contadorCampoOtro+1;

    echo "Campo ".$contadorCampoOtro." agregado<br>";
    
    
    $nombre = obtenerNombreCampo($idCampo)->nombre;
    $nuevoNombre = $nombre." Otro";
    echo $nuevoNombre;
    cambiarNombre($nuevoNombre,$contadorCampoOtro);
    echo "idCampo ".$contadorCampoOtro." nombre ".$nuevoNombre."<br>";
    
    $contadorCampoOtro= $contadorCampoOtro+1;
 

    
}
*/
?>