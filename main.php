<?php
header("Content-Type: text/html;charset=UTF-8");
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 09/06/2019
 * Time: 10:54 PM 
 */

include "lib/validarPermisos.php";
//selección de una opción del menu
$periodo = 0;
$path = "reg/";

if (isset($_GET['p'])) {
    $periodo = $_GET['p'];
}
$misRegistros = 0;
if (isset($_GET['mr'])) {
    $misRegistros = $_GET['mr'];
}
$fichaSeleccionada = 0;
if (isset($_GET['f'])) {
    $fichaSeleccionada = $_GET['f'];
}

//En el caso de guardar el registro de una ficha
if (isset($_POST["btnGuardar"])) {






    $registro = array();

    $Secciones = obtenerSecciones($fichaSeleccionada);
    foreach ($Secciones as $seccion) {

        $seccionObject = (object) [
            'nombreSeccion' => $seccion->nombre,
            'campos' => saveFields($seccion->campos),
            'archivos' => ($seccion->bndFiles) ? saveFiles(decodifica($_POST['folio'])) : "",
        ];

        if (sizeof($seccionObject->campos) > 0 || $seccionObject->archivos !== "" && sizeof($seccionObject->archivos) > 0)
            $registro[] = $seccionObject;
    }



    $autor = "";
    if (isset($_POST["151"]))
        $autor = $_POST["151"];
    else if (isset($_POST["193"]))
        $autor = $_POST["193"];

    $numeroDeCat = "";
    if (isset($_POST["82"]))
        $numeroDeCat = $_POST["82"];
    else if (isset($_POST["148"]))
        $numeroDeCat = $_POST["148"];
    else if (isset($_POST["469"]))
        $numeroDeCat = $_POST["469"];



    if (guardarRegistro(decodifica($_POST["folio"]), $idUsuario, decodifica($_POST["fecha"]), $periodo, $fichaSeleccionada, decodifica($_POST["4"]), $autor, $numeroDeCat) === "EXITOSO") {
        $jsonValores = json_encode($registro, JSON_UNESCAPED_UNICODE);
        $fh = fopen($path . $_POST['folio'] . ".json", 'w');
        fwrite($fh, $jsonValores);
        fclose($fh);
        $folio = decodifica($_POST['folio']);
        //nuevaNotificacion($_POST['folio'],$idUsuario,$idUsuario,$periodo,2,"El borrador ".$folio." ha sido creado, puedes verlo en la sección Mis Registros","Borrador ".$folio." guardado");

        $periodo = 0;
        $fichaSeleccionada = 0;
        $misRegistros = 1;

        header("Location: main.php?mr=1");
    } else {

        header("Location: main.php?mr=1");
    }


}

//En el caso de guardar cambios de un registro
if (isset($_POST["btnActualizar"])) {

    $archivosActuales = array();
    if (isset($_POST["archivosGuardados"])) {
        $archivosGuardados = $_POST["archivosGuardados"];

        $path_reg_directory = "reg/";

        $fh = fopen($path_reg_directory . $_POST['folio'] . ".json", "r");

        $contenido = fread($fh, filesize($path_reg_directory . $_POST['folio'] . ".json"));
        $jsonRegistro = json_decode($contenido, true);
        fclose($fh);

        $bndExiste = false;
        foreach ($jsonRegistro as $seccion) {
            if ($seccion['archivos'] !== "") {
                foreach ($seccion['archivos'] as $archivo) {
                    foreach ($archivosGuardados as $guardado) {
                        $porciones = explode("/", $archivo);

                        if ($porciones[sizeof($porciones) - 1] === $guardado)
                            $bndExiste = true;
                    }

                    if ($bndExiste === false)
                        unlink($archivo) or die("No se puedo borrar el objeto digital");
                    else
                        $archivosActuales[] = $archivo;


                    $bndExiste = false;
                }
            }
        }
    } else {
        $path_reg_directory = "reg/";

        $fh = fopen($path_reg_directory . $_POST['folio'] . ".json", "r");

        $contenido = fread($fh, filesize($path_reg_directory . $_POST['folio'] . ".json"));
        $jsonRegistro = json_decode($contenido, true);
        fclose($fh);
        foreach ($jsonRegistro as $seccion) {
            if ($seccion['archivos'] !== "") {
                foreach ($seccion['archivos'] as $archivo)
                    unlink($archivo) or die("No se puedo borrar el objeto digital");
            }
        }
    }



    $registro = array();


    $archivosNuevos = array();
    $Secciones = obtenerSecciones($fichaSeleccionada); foreach ($Secciones as $seccion) {
        if ($seccion->bndFiles) {
            $archivosNuevos = saveFiles(decodifica($_POST['folio']));

            foreach ($archivosActuales as $archivoPendiente) {
                $archivosNuevos[] = $archivoPendiente;
            }
        }

        $seccionObject = (object) [
            'nombreSeccion' => $seccion->nombre,
            'campos' => saveFields($seccion->campos),
            'archivos' => (sizeof($archivosNuevos) > 0 && $seccion->bndFiles) ? $archivosNuevos : "",
        ];

        //Condicion para ordenar los elementos del objeto y no se pierdan las referencias de imagen en la tabla de registros
        if ($seccionObject->archivos) {
            usort($seccionObject->archivos, function ($a, $b) {
                $aExt = pathinfo($a, PATHINFO_EXTENSION);
                $bExt = pathinfo($b, PATHINFO_EXTENSION);

                if ($aExt == 'jpeg') {
                    return -1;
                } else if ($bExt == 'jpeg') {
                    return 1;
                } else if ($aExt == 'pdf') {
                    return 1;
                } else if ($bExt == 'pdf') {
                    return -1;
                } else {
                    return 0;
                }
            });
        }

        if (sizeof($seccionObject->campos) > 0 || $seccionObject->archivos !== "" && sizeof($seccionObject->archivos) > 0) {
            $registro[] = $seccionObject;
        }
    }

    $autor = "";
    if ($_POST["151"] != null)
        $autor = decodifica($_POST["151"]);
    if ($_POST["193"] != null)
        $autor = decodifica($_POST["193"]);

    if (guardarCambiosRegistro(decodifica($_POST["folio"]), decodifica($_POST["4"]), $autor) === "EXITOSO") {
        unlink($path . $_POST['folio'] . ".json") or die("No se pudo borrar el archivo de registro");
        $jsonValores = json_encode($registro, JSON_UNESCAPED_UNICODE);
        $fh = fopen($path . $_POST['folio'] . ".json", 'w');
        fwrite($fh, $jsonValores);
        fclose($fh);
        $folio = decodifica($_POST['folio']);
        //nuevaNotificacion($_POST['folio'],$idUsuario,$idUsuario,$periodo,2,"Los cambios realizados en el registro ".$folio." han sido guardados con exito",$folio." cambios guardados");
        $periodo = 0;
        $fichaSeleccionada = 0;
        $misRegistros = 1;

        header("Location:  main.php?mr=1");
    }

    /*
        $registro =array();

        $Secciones = obtenerSecciones($fichaSeleccionada);
        foreach ($Secciones as $seccion)
        {

            $seccionObject = (object) [
                'nombreSeccion'=>$seccion->nombre,
                'campos'=>saveFields($seccion->campos),
                'archivos'=>($seccion->bndFiles)?saveFiles(decodifica($_POST['folio'])):"",
            ];

            if(sizeof($seccionObject->campos) > 0 || $seccionObject->archivos !=="" && sizeof($seccionObject->archivos) > 0)
                $registro[]=$seccionObject;
        }


        if(guardarCambiosRegistro( decodifica($_POST["folio"]),decodifica($_POST["4"]))==="EXITOSO")
        {
            unlink($path. $_POST['folio'].".json") or die("No se pudo borrar el archivo de registro");
            $jsonValores = json_encode($registro,JSON_UNESCAPED_UNICODE);
            $fh = fopen( $path. $_POST['folio'].".json", 'w');
            fwrite($fh, $jsonValores);
            fclose($fh);
             $folio= decodifica($_POST['folio']);
            nuevaNotificacion($_POST['folio'],$idUsuario,$idUsuario,$periodo,2,"Los cambios realizados en el registro ".$folio." han sido guardados con exito",$folio." cambios guardados");
          
            $periodo=0;
            $fichaSeleccionada=0;
            $misRegistros=1;

            header ("Location: main.php?mr=1");
        }
    */

}
function saveFields($campos)
{
    $arrayValores = array();
    foreach ($campos as $campo) {
        if ($_POST[$campo->id] !== "0" && $_POST[$campo->id] !== "") {
            $object = (object) [
                'idCampo' => $campo->id,
                'valor' => $_POST[$campo->id],
            ];
            $arrayValores[] = $object;
        }
    }
    return $arrayValores;
}
/*function saveFiles($folio)
{
    $arrayFiles = array();
    $target_dir="uploads/regFiles/";
    // Count total files
    $countfiles = count($_FILES['archivos']['name']);


    // Looping all files
    for($i=0;$i<$countfiles;$i++){
         $filename="";
        for($j=0;$j<5;$j++)
        {
            if(!file_exists($target_dir.$folio.$j.".jpeg") && !file_exists($target_dir.$folio.$j.".pdf"))
            {
                $filename=$target_dir.$folio.microtime(true).$j;
            }
        }
        
        if($filename!=="")
        {
            $target_file = $target_dir . basename($_FILES["archivos"]["name"][$i]);
            $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
            $thumbnailName = "";
            if($fileType === "jpg" || $fileType === "jpeg" || $fileType=== "png") {
                $thumbnailName = $filename . "_THUMB.jpeg";
                $filename .= ".jpeg";
            }
            else if($fileType === "pdf") {
                $filename .= ".pdf";
            }
    
            // Upload file
            if(move_uploaded_file($_FILES['archivos']['tmp_name'][$i],$filename))
            {
                $arrayFiles[]=$filename;
                if ($thumbnailName != "") {
                    require 'lib/thumbsGenerator.php';
                    createThumbnail($filename, $thumbnailName, 150);
                }
            }
        }
    }

    return $arrayFiles;
    
    
    
}*/

function saveFiles($folio)
{
    $arrayFiles = array();
    $target_dir = "uploads/regFiles/";
    $target_dir2 = "..uploads/regFiles/";

    // Count total files
    $countfiles = count($_FILES['archivos']['name']);

    // Looping all files
    $jpegFile = "";
    for ($i = 0; $i < $countfiles; $i++) {
        $filename = "";
        for ($j = 0; $j < 5; $j++) {
            if (!file_exists($target_dir . $folio . $j . ".jpeg") && !file_exists($target_dir . $folio . $j . ".pdf")) {
                $filename = $target_dir . $folio . microtime(true) . $j;
            }
        }

        if ($filename !== "") {
            $target_file = $target_dir . basename($_FILES["archivos"]["name"][$i]);
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $thumbnailName = "";
            if ($fileType === "jpg" || $fileType === "jpeg" || $fileType === "png") {
                $thumbnailName = $filename . "_THUMB.jpeg";
                $filename .= ".jpeg";
                if ($fileType === "jpeg") {
                    $jpegFile = $filename;
                }
            } else if ($fileType === "pdf") {
                $filename .= ".pdf";
            }
            // Upload file
            if (move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $filename)) {
                $arrayFiles[] = $filename;
                if ($thumbnailName != "") {
                    include 'lib/thumbsGenerator.php';
                    createThumbnail($filename, $thumbnailName, 150);
                }
            }
        }
    }

    // Define la función de comparación
    function cmp($a, $b)
    {
        $jpegFile = 0;
        if (strtolower(pathinfo($a, PATHINFO_EXTENSION)) == 'pdf') {
            return 1;
        } elseif ($a == $jpegFile) {
            return -1;
        } else {
            return strcmp($a, $b);
        }
    }

    // Ordena el arreglo
    usort($arrayFiles, "cmp");

    return $arrayFiles;
}



function obtenerDetalleDelRegistro($idRegistro)
{

    require "utils/constants.php";
    require "utils/database_utils.php";

    $registroDB = getRowFromDatabase($TABLA_REGISTROS, $idRegistro);

    if (isset($idRegistro) && $registroDB != "NOT_FOUND") {

        //Encontrar el usuario que hizo el registro
        $registrante = getRowFromDatabase($TABLA_USUARIOS, $registroDB->{"ID_USUARIO"}, "ID");

        //Encontrar el usuario que hizo el registro
        $museo = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $registroDB->{"ID_MUSEO"}, "ID");

        $registroJSON = file_get_contents($DIRECTORIO_REGISTROS . "/" . $idRegistro . ".json");
        if (!$registroJSON) {
            return "JSON_FILE_NOT_FOUND";
        }

        $seccionProcedencia = null;
        $seccionMedidas = null;
        $seccionMedidasBase = null;
        $archivos = null;
        $ubicacionDeRegistro = "sin ubi";

        $registroTemp = json_decode($registroJSON);


        for ($i = 0; $i < sizeof($registroTemp); $i++) {
            $seccionTemp = $registroTemp[$i];


            if (isset($seccionTemp->archivos) && $seccionTemp->archivos != "") {
                $archivos = $seccionTemp->archivos;
                array_splice($registroTemp, $i, 1);
                $i--;
            } else if ($seccionTemp->{"nombreSeccion"} == "Dimensiones") {
                $seccionMedidas = $seccionTemp;
                array_splice($registroTemp, $i, 1);
                $i--;
            } else if ($seccionTemp->{"nombreSeccion"} == "Medidas máximas Base\/Marco" || $seccionTemp->{"nombreSeccion"} == "Medidas máximas Base/Marco") {
                $seccionMedidasBase = $seccionTemp;
                array_splice($registroTemp, $i, 1);
                $i--;
            } else if ($seccionTemp->{"nombreSeccion"} == "Procedencia") {
                $seccionProcedencia = $seccionTemp;
                array_splice($registroTemp, $i, 1);
                $i--;
            } else
                foreach ($seccionTemp->campos as $campoTemp) {

                    $tmpValue = getRowFromDatabase($TABLA_CAMPOS, $campoTemp->idCampo);
                    $campoTemp->nombre = $tmpValue->{"NOMBRE"};
                    $tipoDeCampo = "texto";
                    if ($tmpValue->{"ID_TIPO_CAMPO"} == "3") {
                        $tipoDeCampo = "catalogo";
                        $campoTemp->valorReal = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campoTemp->{"valor"}, "ID")->{"NOMBRE"};
                    }
                    if ($tmpValue->{"ID"} == 8) {
                        $ubicacionDeRegistro = $campoTemp->valorReal;
                    }
                    $campoTemp->tipo = $tipoDeCampo;
                }


        }


        if ($seccionMedidasBase != null) {
            foreach ($seccionMedidasBase->campos as $campoTemp) {

                $tmpValue = getRowFromDatabase($TABLA_CAMPOS, $campoTemp->idCampo);
                $campoTemp->nombre = $tmpValue->{"NOMBRE"};
                $tipoDeCampo = "texto";
                if ($tmpValue->{"ID_TIPO_CAMPO"} == "3") {
                    $tipoDeCampo = "catalogo";
                    $campoTemp->valorReal = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campoTemp->{"valor"}, "ID")->{"NOMBRE"};
                }
                if ($tmpValue->{"ID"} == 8) {
                    $ubicacionDeRegistro = $campoTemp->valorReal;
                }
                $campoTemp->tipo = $tipoDeCampo;
            }
        }


        /*
              foreach ($registroTemp as $seccionTemp){
                  
                  if(isset($seccionTemp->archivos) && $seccionTemp->archivos != "")
                      $archivos = $seccionTemp->archivos;
                  if($seccionTemp->{"nombreSeccion"} == "Medidas máximas"){
                      $seccionMedidas = $seccionTemp;
                  } else if($seccionTemp->{"nombreSeccion"} == "Medidas Base/Marco"){
                      $seccionMedidasBase = $seccionTemp;
                  } else if ($seccionTemp->{"nombreSeccion"} == "Procedencia"){
                      $seccionProcedencia = $seccionTemp;
                  } else
                      foreach ($seccionTemp->campos as $campoTemp) {
                          
                          $tmpValue = getRowFromDatabase($TABLA_CAMPOS, $campoTemp->idCampo);
                          $campoTemp->nombre = $tmpValue->{"NOMBRE"};
                          $tipoDeCampo = "texto";
                          if($tmpValue->{"ID_TIPO_CAMPO"} == "3"){
                              $tipoDeCampo = "catalogo";
                              $campoTemp->valorReal = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campoTemp->{"valor"}, "ID")->{"NOMBRE"};
                          }
                          $campoTemp->tipo = $tipoDeCampo;
                      }
              }
              */

        $registro = new stdClass();
        $registro->procedencia = $seccionProcedencia;
        $registro->medidas = $seccionMedidas;
        $registro->medidasBase = $seccionMedidasBase;
        $registro->archivos = $archivos;
        $registro->id = $idRegistro;
        $registro->{"id_registrante"} = $registrante->{"ID"};
        $registro->{"id_coleccion"} = $registroDB->{"ID_PERIODO"};
        $registro->{"id_ficha"} = $registroDB->{"ID_FICHA"};
        $registro->status = filterRegisterStatus($registroDB->{"ID_ESTATUS_REGISTRO"});
        $registro->usuario = $registrante->{"NOMBRE"} . " " . $registrante->{"APELLIDOS"};
        $registro->museo = $museo->{"NOMBRE"};
        $registro->ubicacion = $ubicacionDeRegistro;
        $registro->secciones = $registroTemp;
        return $registro;
    } else {
        return "NOT_FOUND";
    }
}


?>
<!DOCTYPE html>
<html lang="es-MX">

<head>


    <title>Patrimonio Cultural Estado de México</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta http-equiv="cache-control" content="max-age-0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Augment Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
    <script
        type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- Graph CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'
        type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/amcharts.js"></script>
    <script src="js/serial.js"></script>
    <script src="js/light.js"></script>
    <script src="js/radar.js"></script>

    <script src="js/html2canvas.js"></script>


    <link href="css/barChart.css" rel='stylesheet' type='text/css' />
    <link href="css/fabochart.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/ficha.css" type='text/css' />

    <!--clock init-->
    <script src="js/css3clock.js"></script>
    <!--Easy Pie Chart-->
    <!--skycons-icons-->
    <script src="js/skycons.js"></script>

    <script src="js/jquery.easydropdown.js"></script>
    <script type="text/javascript">
        $(document).on("keypress", 'form', function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });


    </script>
</head>

<body>



    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">
                <!-- header-starts -->
                <div class="header-section">
                    <!--menu-right-->
                    <div class="top_menu">
                        <!--/profile_details-->
                        <div class="profile_details_left">
                            <ul class="nofitications-dropdown">

                                <?php $notificacionesPendientes = obtenerNotificacionesPendientes($idUsuario); ?>
                                <li class="dropdown note">
                                    <a href="main.php?mr=7" class="dropdown-toggle"><i class="fa fa-bell-o"></i> <span
                                            class="badge" id="numGeneralNotifications">
                                            <?php echo sizeof($notificacionesPendientes); ?>
                                        </span></a>
                                </li>
                                <li class="dropdown note">
                                    <a href="salir.php" class="dropdown-toggle"><i class="lnr lnr-power-switch"></i></a>

                                </li>
                                <div class="clearfix"></div>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <!--//profile_details-->
                    </div>
                    <!--//menu-right-->
                    <div class="clearfix"></div>
                </div>
                <!-- //header-ends -->























                <div class="outter-wp">




                    <?php
                    if ($misRegistros == 1) {
                        ?>

                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="sub-heard-part">
                                    <ol class="breadcrumb m-b-0">
                                        <li><a href="main.php?mr=5">Dashboard</a></li>
                                        <li class="active">Mis Registros</li>
                                    </ol>
                                </div>
                            </div>

                        </div>



                        <div class="row ">
                            <div class="col-md-6">
                                <h3 class="sub-tittle pro">Mis Registros</h3>
                            </div>
                            <div class="col-md-6">

                            </div>
                        </div>

                        <div class="candile">
                            <div class="candile-inner">

                                <?php
                                include "misRegistros.php";
                                ?>

                            </div> <!-- end candile-inner -->
                        </div> <!-- end candile -->

                        <?php
                    } else if ($misRegistros == 2) {
                        //PENDIENTEEEEEEEE                        
                        include "mostrarRegistro.php";
                    } else if ($misRegistros == 3) {
                        ?>

                                <!-- migas de pan -->
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="sub-heard-part">
                                            <ol class="breadcrumb m-b-0">
                                                <li><a href="main.php?mr=5">Dashboard</a></li>
                                                <li class="active">Usuarios</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <!-- títulos -->
                                <div class="row ">
                                    <div class="col-md-6">
                                        <h3 class="sub-tittle pro">Usuarios</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="botones">
                                            <a href="main.php?mr=4" class="btn btn-exportar"><i class="fa fa-plus"></i> Agregar
                                                usuario</a>
                                        </div>
                                    </div>
                                </div>


                                <div class="candile">
                                    <div class="candile-inner">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="buscar">
                                                    <form>
                                                        <input type="text" value="" class="v2" placeholder="Buscar"
                                                            onkeyup="buscarPorNombre(this.value)">
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="dataTables_length">
                                                    <label>Mostrar
                                                        <select name="example_length" aria-controls="example" class=""
                                                            onchange="cambiarLongitudDeTabla(this)">
                                                            <option value="10">10</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                        </select>
                                                        registros
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        include "mostrarUsuarios.php";
                                        ?>

                                    </div> <!-- end candile-inner -->
                                </div> <!-- end candile -->

                                <?php
                    } else if ($misRegistros == 4) {
                        //PENDIENTEEEEEEEE                        
                        include "nuevoUsuario.php";
                    } else if ($misRegistros == 5) {
                        ?>



                                        <!--     

                <div class="custom-widgets">
                    <div class="row-one">
                        <div class="col-md-3 widget">
                            <div class="stats-left ">
                                <h5>Colección</h5>
                                <h4> Paleontológico</h4>
                            </div>
                            <div class="stats-right">
                                <label>2905</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-mdl">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Arqueológico</h4>
                            </div>
                            <div class="stats-right">
                                <label>3881</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-thrd">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Virreinal</h4>
                            </div>
                            <div class="stats-right">
                                <label>2510</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-last">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Arte moderno</h4>
                            </div>
                            <div class="stats-right">
                                <label>3402</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                </div>
                <br>
                <div class="custom-widgets">

                    <div class="row-one">
                        <div class="col-md-3 widget states-five">
                            <div class="stats-left ">
                                <h5>Específicas</h5>
                                <h4>Culturas populares</h4>
                            </div>
                            <div class="stats-right">
                                <label>5900</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-six">
                            <div class="stats-left">
                                <h5>Específicas</h5>
                                <h4>Numismática</h4>
                            </div>
                            <div class="stats-right">
                                <label>1857</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-seven">
                            <div class="stats-left">
                                <h5>Específicas</h5>
                                <h4>Historia natural</h4>
                            </div>
                            <div class="stats-right">
                                <label>5101</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-eight">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Objeto histórico</h4>
                            </div>
                            <div class="stats-right">
                                <label>3098</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>

                        <div class="clearfix"> </div>
                    </div>
                </div>
                

                        
                <div class="candile">
                    <div class="candile-inner">
                    
                    -->



                                    <?php
                                    include "mostrarRegistrosCompletos.php";
                                    ?>

                                    </div> <!-- end candile-inner -->
                                </div> <!-- end candile -->


                                <?php
                    } else if ($misRegistros == 8){
                        include "dashFiltro.php";

                    } else if ($misRegistros == 6) {
                        ?>


                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <div class="sub-heard-part">
                                                <ol class="breadcrumb m-b-0">
                                                    <li><a href="main.php?mr=5">Dashboard</a></li>
                                                    <li class="active">Papelera</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-6">
                                            <h3 class="sub-tittle pro">Papelera</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="botones"></div>
                                        </div>
                                    </div>

                                    <div class="candile">
                                        <div class="candile-inner">
                                        <?php
                                        include "misRegistrosPapelera.php";
                                        ?>

                                        </div> <!-- end candile-inner -->
                                    </div> <!-- end candile -->

                                    <?php
                    } else if ($misRegistros == 7) {
                        ?>

                                        <!--sub-heard-part-->
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="sub-heard-part">
                                                    <ol class="breadcrumb m-b-0">
                                                        <li><a href="main.php?mr=5">Dashboard</a></li>
                                                        <li class="active">Notificaciones</li>
                                                    </ol>
                                                </div>
                                            </div>

                                        </div>
                                        <!--//sub-heard-part-->
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <h3 class="sub-tittle pro">Notificaciones</h3>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                        </div>

                                        <div class="candile">
                                            <div class="candile-inner">

                                                <?php
                                                include "notificaciones.php";
                                                ?>

                                            </div> <!-- end candile-inner -->
                                        </div> <!-- end candile -->

                                        <?php
                    } else if ($misRegistros == 25) {
                        include "detalleDeFicha.php";
                    } else if ($misRegistros == 31) {
                        include "user_profile.php";
                    } else if ($misRegistros == 32) {
                        include "update_user_profile.php";
                    } else if ($misRegistros == 33) {
                        include "new_field.php";
                    } else if ($misRegistros == 34) {
                        include "new_catalog.php";
                    } else {
                        ?>



                                                            <?php
                                                            if ($periodo !== 0 && $fichaSeleccionada == 0) {
                                                                include "mostrarFichas.php";
                                                            } else if ($fichaSeleccionada !== 0 && $misRegistros == 0) {
                                                                ?>
                                                                    <script type="text/javascript">
                                                                        var arrayRelaciones = <?php echo json_encode(obtenerRelacionesCampos($fichaSeleccionada)); ?>;
                                                                    </script>
                                                                    <?php
                                                                    $relacionesValoresCampos = obtenerRelacionesCampos($fichaSeleccionada);
                                                                    $microtime = microtime(true); //fecha y hora de microsegundos
                                                                    $now = DateTime::createFromFormat('U.u', $microtime); //fecha con formato
                                                                    ?>



                                                                    <div class="row">
                                                                        <div class="col-md-6"></div>
                                                                        <div class="col-md-6">
                                                                            <div class="sub-heard-part">
                                                                                <ol class="breadcrumb m-b-0">
                                                                                    <li><a href="main.php?mr=5">Dashboard</a></li>
                                                                                    <li><a href="main.php?p=<?php echo $periodo; ?>">Registro</a></li>
                                                                                    <li class="active">
                                                                                    <?php echo obtenerNombreFicha($fichaSeleccionada); ?>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>

                                                                    </div>



                                                                    <div class="row ">
                                                                        <div class="col-md-6">
                                                                            <h3 class="sub-tittle pro">Nuevo registro</h3>
                                                                        </div>
                                                                        <div class="col-md-6">

                                                                        </div>
                                                                    </div>




                                                                    <div class="candile">
                                                                        <div class="candile-inner">

                                                                            <!--Inicia registro-->
                                                                            <div class="graph">
                                                                                <div id="cabecera">
                                                                                    <h3 class="title">Ficha de registro</h3>

                                                                                </div>
                                                                                <div id="formulario">
                                                                                    <form id="ficha-form" role="form"
                                                                                        action="main.php?op=2&p=<?php echo $periodo; ?>&f=<?php echo $fichaSeleccionada; ?>"
                                                                                        method="post" enctype="multipart/form-data">

                                                                                        <!--<div class="fila">-->
                                                                                        <div class="col-md-12 campo-formulario">
                                                                                            <label>
                                                                                            <?php echo obtenerNombreFicha($fichaSeleccionada); ?>
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="col-md-0 campo-formulario">
                                                                                        </div>
                                                                                        <div class="col-md-6 campo-formulario">
                                                                                            <div class="col-md-4">
                                                                                                <label for="fecha">Fecha </label>
                                                                                            </div>
                                                                                            <div class="col-md-8">
                                                                                                <input type="text" id="fecha" name="fecha" size="25" readonly class="x"
                                                                                                    value="<?php echo $now->format("d-m-Y"); ?>" maxlength="50">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6 campo-formulario" id="colFolio">
                                                                                            <div class="col-md-4">
                                                                                                <label for="folio">Folio </label>
                                                                                            </div>
                                                                                            <div class="col-md-8">
                                                                                                <input type="text" id="folio" name="folio" size="25" readonly class="x"
                                                                                                    value="<?php echo "REG" . $fichaSeleccionada . "-" . $microtime; ?>"
                                                                                                    maxlength="50">
                                                                                            </div>
                                                                                        </div>


                                                                                        <?php
                                                                                        $arraySecciones = obtenerSecciones($fichaSeleccionada);
                                                                                        foreach ($arraySecciones as $seccion) {
                                                                                            $dosCampos = "";
                                                                                            $counterCampos = 0;
                                                                                            ?>
                                                                                            <div class="cabecera">
                                                                                                <h3 class="sub-title">
                                                                                                <?php echo $seccion->nombre; ?>
                                                                                                </h3>
                                                                                            </div>

                                                                                            <?php
                                                                                            $sizeFields = sizeof($seccion->campos);






                                                                                            $camposTmp = $seccion->campos;
                                                                                            for ($i = 0; $i < $sizeFields; $i++) {
                                                                                                $campo = $camposTmp[$i];
                                                                                                $obligatorio = "";
                                                                                                if ($campo->obligatorio) {
                                                                                                    $obligatorio = "required";
                                                                                                    $campo->nombre = "<span style='color: red; '>* </span>" . $campo->nombre;
                                                                                                }
                                                                                                if ($campo->tipo == 1) {
                                                                                                    //Es un campo de texto
                                                                                                    $nombrePeriodo = "";
                                                                                                    if ($campo->id == 2)
                                                                                                        $nombrePeriodo = obtenerNombrePeriodo($periodo);
                                                                                                    $dosCampos .= "<div class='col-md-6  campo-formulario' id='col" . $campo->id . "'><div class='col-md-4'><label for='" . $campo->id . "'>" . $campo->nombre . "</label></div><div class='col-md-8'><input id='" . $campo->id . "' name='" . $campo->id . "' value='" . $nombrePeriodo . "' type='text' class='x' " . $obligatorio . "></div></div>";
                                                                                                    if (isset($camposTmp[$i + 1]) && $camposTmp[$i + 1]->tipo == 2 && $i % 2 != 1) {
                                                                                                        $dosCampos .= "<div class='col-md-6  campo-formulario'></div>";
                                                                                                    }
                                                                                                    if (!isset($camposTmp[$i + 1]) && ($i % 2) != 1) {
                                                                                                        $dosCampos .= "<div class='col-md-6  campo-formulario'></div>";
                                                                                                    }
                                                                                                } else if ($campo->tipo == 2) {
                                                                                                    //$dosCampos.="<div class='col-md-12  campo-formulario' id='col".$campo->id."'><div class='col-md-2'><label for='".$campo->id."'>".$campo->nombre."</label></div><div class='col-md-10'><textarea id='".$campo->id."' name='".$campo->id."'  rows='6' class='x' ".$obligatorio."></textarea></div></div><div class='col-md-0  campo-formulario'></div>";
                                                                                                    $dosCampos .= "<div class='col-md-12 campo-formulario' id='col" . $campo->id . "'><div class='col-md-2'><label for='" . $campo->id . "'>" . $campo->nombre . "</label></div><div class='col-md-10'><textarea id='" . $campo->id . "' name='" . $campo->id . "' rows='6' cols='30' class='x' " . $obligatorio . " wrap='soft'></textarea></div></div><div class='col-md-0 campo-formulario'></div>";
                                                                                                } else if ($campo->tipo == 3) {
                                                                                                    $opcionesString = "";
                                                                                                    if ($campo->id != 67 && $campo->id != 68 && $campo->id != 69)
                                                                                                        foreach ($campo->opciones as $opcion) {
                                                                                                            $opcionesString .= " <option value='" . $opcion->id . "'>" . $opcion->nombre . "</option>";
                                                                                                        }
                                                                                                    $bndSeleccion = "";
                                                                                                    if ($campo->id == 4 || $campo->id == 5)
                                                                                                        $bndSeleccion = "disabled";
                                                                                                    $dosCampos .= "<div class='col-md-6 campo-formulario' id='col" . $campo->id . "'>
                                                            <div class='col-md-4'><label for='" . $campo->id . "'>" . $campo->nombre . "</label></div>
                                                            <div class='col-md-8'>
                                                              <div class='caja'>
                                                                    <select id='" . $campo->id . "' name='" . $campo->id . "' size='1' class='selector' onchange='camposValor(" . $campo->id . "," . $seccion->id . ")' " . $obligatorio . ">
                                                                        <option value='' selected " . $bndSeleccion . ">Selecciona una opci&oacuten</option>
                                                                        " . $opcionesString . "
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>";
                                                                                                    $opcionesString = "";
                                                                                                    if (isset($camposTmp[$i + 1]) && $camposTmp[$i + 1]->tipo == 2 && (($i % 2) != 1)) {
                                                                                                        $dosCampos .= "<div class='col-md-6  campo-formulario'></div>";
                                                                                                    }
                                                                                                }

                                                                                            }

                                                                                            echo $dosCampos;
                                                                                            $dosCampos = "";








                                                                                            /*
                                                                                            
                                                                                            
                                                                                            $counterFields=0;
                                                                                            $counterRows=0;
                                                                                            $spacesCounter = 0;
                                                                                            foreach ($seccion->campos as $campo) {
                                                                                                if($campo->obligatorio)
                                                                                                    $campo->nombre = "<span style='color: red; '>* </span>".$campo->nombre;
                                                                                                if($campo->tipo == 1) {
                                                                                                    $nombrePeriodo="";
                                                                                                    if($campo->id==2)
                                                                                                        $nombrePeriodo= obtenerNombrePeriodo($periodo);
                                                                                                    $obligatorio= ($campo->obligatorio)?"required": '';
                                                                                                    $dosCampos.="<div class='col-md-6  campo-formulario' id='col".$campo->id."'><div class='col-md-4'><label for='".$campo->id."'>".$campo->nombre."</label></div><div class='col-md-8'><input id='".$campo->id."' name='".$campo->id."' value='".$nombrePeriodo."' type='text' class='x' ".$obligatorio."></div></div>";
                                                                                                    $counterCampos++;
                                                                                                    $spacesCounter ++;
                                                                                                }else if ($campo->tipo==2) {
                                                                                                    $obligatorio= ($campo->obligatorio)?"required": '';
                                                                                                    $dosCampos.="<div class='col-md-12  campo-formulario' id='col".$campo->id."'><div class='col-md-2'><label for='".$campo->id."'>".$campo->nombre."</label></div><div class='col-md-10'><textarea id='".$campo->id."' name='".$campo->id."'  rows='6' class='x' ".$obligatorio."></textarea></div></div>";
                                                                                                    $counterCampos = 2;
                                                                                                }else if ($campo->tipo==3) {
                                                                                                    $opcionesString="";
                                                                                                    if($campo->id != 67 && $campo->id != 68 && $campo->id != 69)
                                                                                                        foreach ($campo->opciones as $opcion) {
                                                                                                            $opcionesString .=" <option value='". $opcion->id."'>".$opcion->nombre."</option>";
                                                                                                        }
                                                                                                    $obligatorio= ($campo->obligatorio)?"required": '';
                                                                                                    $bndSeleccion="";
                                                                                                    if($campo->id==4 || $campo->id== 5)
                                                                                                        $bndSeleccion="disabled";
                                                                                                    $dosCampos.="<div class='col-md-6 campo-formulario' id='col".$campo->id."'>
                                                                                                                            <div class='col-md-4'><label for='".$campo->id."'>".$campo->nombre."</label></div>
                                                                                                                            <div class='col-md-8'>
                                                                                                                              <div class='caja'>
                                                                                                                                    <select id='".$campo->id."' name='".$campo->id."' size='1' class='selector' onchange='camposValor(".$campo->id.",".$seccion->id.")' ".$obligatorio.">
                                                                                                                                        <option value='' selected ".$bndSeleccion.">Selecciona una opci&oacuten</option>
                                                                                                                                        ".$opcionesString."
                                                                                                                                    </select>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>";
                                                                                                    $opcionesString="";
                                                                                                    $counterCampos++;
                                                                                                }
                                                                                                $filaColor="";
                                                                                                if($counterCampos==2) {
                                                                                                    if($counterRows%2!=0)
                                                                                                        $filaColor="gris";
                                                                                            ?>

                                                                                                    <?php
                                                                                                    echo $dosCampos;
                                                                                                    $counterCampos=0;
                                                                                                    $dosCampos="";
                                                                                                    ?>
                                                                                            <?php
                                                                                                    $counterRows++;
                                                                                                }

                                                                                                $counterFields++;
                                                                                                if($counterFields == $sizeFields && $dosCampos!=="") {
                                                                                                    if($counterRows%2!=0)
                                                                                                        $filaColor="gris";
                                                                                            ?>
                                                                                                                        <?php
                                                                                                                        echo $dosCampos;
                                                                                                                        $counterCampos=0;
                                                                                                                        $dosCampos="";
                                                                                                                        ?>
                                                                                            <?php
                                                                                                    $counterFields=0;
                                                                                                }
                                                                                                if(sizeFields % 2 == 1){
                                                                                                   echo "<div class='col-md-0 campo-formulario'></div>";
                                                                                                }
                                                                                            }

                                                                */
                                                                                            if ($seccion->bndFiles == 1) {
                                                                                                ?>
                                                                                                <div class="col-md-6 campo-formulario">
                                                                                                    <div class="col-md-4"><label>Objeto digital</label></div>
                                                                                                    <div class="col-md-8">
                                                                                                        <label style=" display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 200;
    font-size: 0.8em;
    color: #777;">Selecciona hasta 5 objetos a la vez</label>
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="col-md-6 campo-formulario">
                                                                                                    <div class="col-md-8">
                                                                                                        <label id="objectsCounterSelected" for="archivos" class="a_demo_four">Cargar
                                                                                                            aquí (0 Objetos)</label>
                                                                                                        <input style="display:none;" type="file" class="form-control" id="archivos"
                                                                                                            name="archivos[]" multiple="multiple" />

                                                                                                    </div>
                                                                                                    <div class="col-md-4"></div>

                                                                                                </div>

                                                                                                <script>



                                                                                                    $("#archivos").on("change", function () {

                                                                                                        if ($("#archivos")[0].files.length > 5) {

                                                                                                            alert("Solo puedes subir un máximo de 5 archivos");
                                                                                                            $("#archivos").val('');
                                                                                                            document.getElementById('objectsCounterSelected').innerHTML = 'Cargar aquí (0 Objetos)';
                                                                                                        } else {
                                                                                                            var totalSize = 0;
                                                                                                            var files = $('#archivos')[0].files;

                                                                                                            for (var i = 0; i < files.length; i++) {
                                                                                                                // calculate total size of all files
                                                                                                                totalSize += files[i].size;
                                                                                                            }

                                                                                                            document.getElementById('objectsCounterSelected').innerHTML = 'Cargar aquí (' + files.length + ' Objetos)';
                                                                                                            if (totalSize > 104857600) {
                                                                                                                alert("El tamaño total es de 10MB");
                                                                                                                $("#archivos").val('');
                                                                                                                document.getElementById('objectsCounterSelected').innerHTML = 'Cargar aquí (0 Objetos)';
                                                                                                            }
                                                                                                        }
                                                                                                    });
                                                                                                </script>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                            <?php

                                                                                        }
                                                                                        ?>
                                                                                        <button style="position: absolute; right: 0; bottom: -4.5em;" type="submit"
                                                                                            name="btnGuardar" id="btnGuardar" class="btn guardar">GUARDAR</button>
                                                                                    </form>
                                                                                </div>
                                                                                <div class="clearfix"></div>
                                                                            </div>
                                                                            <div class="botones" style="height:6em;">
                                                                                <!--<button type="submit" class="btn guardar" onclick="sendForm()">GUARDAR</button>-->
                                                                            </div>
                                                                            <div class="clearfix"></div>


                                                                        </div>
                                                                    </div>
                                                                    <script type="text/javascript">
                                                                        var arrayRelaciones = <?php echo json_encode(obtenerRelacionesCampos($fichaSeleccionada)); ?>;
                                                                        arrayRelaciones.forEach(function (relacion) {
                                                                            var elemento = document.getElementById("col" + relacion.id_campo);
                                                                            if (elemento != null)
                                                                                elemento.style.display = "none";
                                                                        });

                                                                        colorearFormulario();

                                                                        function colorearFormulario() {
                                                                            var visibles = new Array();
                                                                            var visibleDivs = new Array();

                                                                            $(".fake-form-field").remove();

                                                                            $.each($("form > div"), function (index, value) {
                                                                                if ($(value).css("display") == "block") {
                                                                                    visibleDivs.push(value);
                                                                                }
                                                                            });
                                                                            var startIndex = 0;
                                                                            var limitIndex;
                                                                            $.each(visibleDivs, function (index, value) {
                                                                                if ($(value).hasClass("cabecera")) {
                                                                                    //console.log("Displayed (" + index + ")", value);
                                                                                    limitIndex = index;
                                                                                    if ((limitIndex - startIndex) % 2 == 0) {
                                                                                        //console.log("correcto (" + limitIndex + " - " + startIndex + ")", value);
                                                                                    } else {
                                                                                        $('<div class="col-md-6 campo-formulario fake-form-field"><div>').insertBefore(value);
                                                                                        //console.log("incorrecto (" + limitIndex + " - " + startIndex + ")", value);
                                                                                    }
                                                                                    startIndex = limitIndex + 1;
                                                                                } else {
                                                                                    //console.log("Nothing (" + index + ")", value);
                                                                                }
                                                                            });



                                                                            $.each($(".campo-formulario"), function (index, value) {

                                                                                if ($(value).css("display") == "block") {
                                                                                    console.log("added");
                                                                                    visibles.push(value);
                                                                                }
                                                                            });


                                                                            for (i = 0; i < visibles.length; i++) {
                                                                                if (i % 4 == 0 || i % 4 == 1) {
                                                                                    $(visibles[i]).css("background", "white");
                                                                                } else
                                                                                    $(visibles[i]).css("background", "#f2f2f2");
                                                                            }
                                                                        }




                                                                        /* Original */
                                                                        function colorearFormularioOriginal() {
                                                                            var visibles = new Array();
                                                                            var visibleDivs = new Array();

                                                                            $(".fake-form-field").remove();

                                                                            $.each($("form > div"), function (index, value) {
                                                                                if ($(value).css("display") == "block") {
                                                                                    console.log("added");
                                                                                    visibleDivs.push(value);
                                                                                }
                                                                            });
                                                                            var startIndex = 0;
                                                                            var limitIndex;
                                                                            $.each(visibleDivs, function (index, value) {
                                                                                if ($(value).hasClass("cabecera")) {
                                                                                    limitIndex = index;
                                                                                    if ((limitIndex - startIndex) % 2 == 0) {
                                                                                        console.log("correcto (" + limitIndex + " - " + startIndex + ")", value);
                                                                                    } else {
                                                                                        $('<div class="col-md-6 campo-formulario fake-form-field"><div>').insertBefore(value);
                                                                                        console.log("incorrecto (" + limitIndex + " - " + startIndex + ")", value);
                                                                                    }
                                                                                    startIndex = limitIndex + 1;
                                                                                }
                                                                            });



                                                                            $.each($(".campo-formulario"), function (index, value) {

                                                                                if ($(value).css("display") == "block") {
                                                                                    console.log("added");
                                                                                    visibles.push(value);
                                                                                }
                                                                            });


                                                                            for (i = 0; i < visibles.length; i++) {
                                                                                if (i % 4 == 0 || i % 4 == 1) {
                                                                                    $(visibles[i]).css("background", "white");
                                                                                } else
                                                                                    $(visibles[i]).css("background", "#f2f2f2");
                                                                            }
                                                                        }
                                                                    </script>
                                                                    <?php
                                                            }
                    }
                    ?>



        </div>
        <!--//outer-wp-->





















    </div>


    <!--footer section start-->
    <footer>
        <img class="centrar" src="images/logos_foot.png" width="450" height="51">
    </footer>
    <!--footer section end-->
    </div>

    <!--//content-inner-->
    <!--/sidebar-menu-->
    <div class="sidebar-menu">
        <header class="logo">
            <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="#"> <span id="logo"> <img
                        src="images/logo_patrimonio.png"></span>
                <!--<img id="logo" src="" alt="Logo"/>-->
            </a>
        </header>
        <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
        <!--/down-->
        <div class="down">
            <a href="#"><img src="<?php echo $imagen; ?>" style="width: 40%;"></a>
            <a href="#"><span class=" name-caret">
                    <?php echo $nombre . " " . $apellidos; ?>
                </span></a>
            <p>
                <?php echo $rolNombre; ?>
            </p>
            <ul>
                <li><a class="tooltips" href="main.php?mr=31"><span>Perfil</span><i class="lnr lnr-user"></i></a></li>
                <li><a class="tooltips" href="salir.php"><span>Salir</span><i class="lnr lnr-power-switch"></i></a></li>
            </ul>
        </div>
        <!--//down-->
        <div class="menu">
            <ul id="menu">
                <li><a href="main.php?mr=5"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>

                <?php
                if (!empty($global_permisos)) {
                    if (array_search(3, $global_permisos) !== false) {
                        ?>
                        <li id="menu-academico"><a href="#"><i class="fa fa-pencil-square-o"></i> <span>Mis registros</span>
                                <span class="fa fa-angle-right" style="float: right"></span></a>
                            <ul id="menu-comunicacao-sub">
                                <li id="menu-mensagens"><a href="#">Nuevo <i class="fa fa-angle-right"
                                            style="float: right; margin-right: -8px; margin-top: 2px;"></i></a>
                                    <ul id="menu-mensagens-sub">
                                        <?php
                                        $colecciones = obtenerColeccion(1);
                                        foreach ($colecciones as $coleccion) {
                                            if (sizeof($coleccion->fichas) > 1) {
                                                ?>
                                                <li id="menu-mensagens-recebidas"><a
                                                        href="main.php?p=<?php echo $coleccion->id; ?>"><?php echo $coleccion->coleccion; ?></a>
                                                </li>
                                                <?php
                                            } else {
                                                ?>
                                                <li id="menu-mensagens-recebidas"><a
                                                        href="main.php?p=<?php echo $coleccion->id; ?>&f=<?php echo $coleccion->fichas[0]->id; ?>"><?php echo $coleccion->coleccion; ?></a>
                                                </li>
                                                <?php
                                            }

                                        }
                                        ?>
                                        <li id="menu-mensagens" style="min-width:280px;"><a href="#">Colecciones específicas <i
                                                    class="fa fa-angle-right"
                                                    style="float: right; margin-right: -8px; margin-top: 2px;"></i></a>
                                            <ul id="menu-mensagens-sub-sub">
                                                <?php
                                                $colecciones = obtenerColeccion(2);
                                                foreach ($colecciones as $coleccion) {
                                                    if (sizeof($coleccion->fichas) > 1) {
                                                        ?>
                                                        <li id="menu-mensagens-enviadas" style="width:220px"><a
                                                                href="main.php?p=<?php echo $coleccion->id; ?>"><?php echo $coleccion->coleccion; ?></a>
                                                        </li>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <li id="menu-mensagens-enviadas" style="width:220px"><a
                                                                href="main.php?p=<?php echo $coleccion->id; ?>&f=<?php echo $coleccion->fichas[0]->id; ?>"><?php echo $coleccion->coleccion; ?></a>
                                                        </li>
                                                        <?php
                                                    }

                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li id="menu-arquivos"><a href="main.php?mr=1">Mis registros</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    if (array_search(7, $global_permisos) !== false) {

                        ?>
                        <li><a href="main.php?mr=3"><i class="fa fa-users"></i> <span>Usuarios</span></a></li>
                        <?php
                    }
                    if (array_search(10, $global_permisos) !== false) {
                        ?>

                        <li id="menu-academico"><a href="#"><i class="fa fa-check-square-o"></i> <span>Campos</span> <span
                                    class="fa fa-angle-right" style="float: right"></span></a>
                            <ul id="menu-academico-sub">
                                <li id="menu-academico-avaliacoes"><a href="main.php?mr=33">Nuevo campo</a></li>
                                <!-- <li id="menu-academico-boletim"><a href="#">Mis campos</a></li> -->
                            </ul>
                        </li>
                        <?php
                    }

                    if (array_search(11, $global_permisos) !== false) {
                        ?>
                        <li id="menu-academico"><a href="#"><i class="fa fa-files-o"></i><span>Catálogos</span><span
                                    class="fa fa-angle-right" style="float: right"></span></a>
                            <ul>
                                <li><a href="main.php?mr=34"> Nuevo catálogo</a></li>

                            </ul>
                        </li>
                        <?php
                    }
                }

                //                    }
                ?>
                <li id="menu-academico"><a href="main.php?mr=6"><i class="fa fa-trash-o"></i> <span>Papelera</span> </a>

                </li>



            </ul>


        </div>
    </div>
    <div class="clearfix"></div>
    </div>

    <script>
        var toggle = true;

        $(".sidebar-icon").click(function () {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({ "position": "absolute" });
            }
            else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function () {
                    $("#menu span").css({ "position": "relative" });
                }, 400);
            }

            toggle = !toggle;
        });
    </script>
    <!--js -->
    <link rel="stylesheet" href="css/vroom.css">
    <script type="text/javascript" src="js/vroom.js"></script>
    <script type="text/javascript" src="js/TweenLite.min.js"></script>
    <script type="text/javascript" src="js/CSSPlugin.min.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"
        integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/"
        crossorigin="anonymous"></script>
    <!--
<script src="js/jspdf.js"></script>
-->
    <script src="js/pdf-builder.js"></script>



    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>


    <script type="text/javascript">


        function camposValor(id, nombreSeccion) {

            //if(nombreSeccion == 6 || nombreSeccion == 13 || nombreSeccion ==20 || nombreSeccion ==39 || nombreSeccion ==48 || nombreSeccion ==57 || nombreSeccion == 125 || nombreSeccion == 126 || nombreSeccion == 127 || nombreSeccion == 128 || nombreSeccion == 129 || nombreSeccion == 130 || nombreSeccion == 131 || nombreSeccion == 132)
            if (id == 66 || id == 67 || id == 68)
                relacionOpcionOpcion(id);
            else
                relacionOpcionCampo(id);

            colorearFormulario();


        }

        function relacionOpcionOpcion(id) {
            var valorSeleccionado = document.getElementById(id).value;
            var paisSeleccionado = 0;
            var estadoSeleccionado = 0;
            var municipioSeleccionado = 0;
            var campoMunicipio = document.getElementById("68");
            var campoLocalidad = document.getElementById("69");

            if (id == 66) {
                paisSeleccionado = valorSeleccionado;
                campoMunicipio.innerHTML = "<option value='' selected >Selecciona una opci&oacuten</option>";
                campoLocalidad.innerHTML = "<option value='' selected >Selecciona una opci&oacuten</option>";
            }
            else if (id == 67) {

                estadoSeleccionado = valorSeleccionado;
                campoLocalidad.innerHTML = "<option value='' selected >Selecciona una opci&oacuten</option>";
            }
            else {

                municipioSeleccionado = valorSeleccionado;
            }

            if (id != 69) {

                $.get("lib/obtenerDatosdeOpciones.php", { campoOrigen: id, pais: paisSeleccionado, estado: estadoSeleccionado, municipio: municipioSeleccionado }, function (data) {

                    var arrayValores = JSON.parse(data);

                    var campoDestino = document.getElementById(arrayValores.campoDestino);
                    campoDestino.innerHTML = "<option value='' selected >Selecciona una opci&oacuten</option>";
                    arrayValores.valores.forEach(function (valor) {
                        campoDestino.innerHTML += "<option value='" + valor.id_valor + "' >" + valor.nombre + "</option>"
                    });


                });
            }

        }

        function relacionOpcionCampo(id) {
            var valorSeleccionado = document.getElementById(id).value;
            console.log("buscando derivados de (" + valorSeleccionado + ") en: ");
            console.log(arrayRelaciones);
            var toDisplay = [];
            arrayRelaciones.forEach(function (relacion) {

                if (id == relacion.id_campo_origen) {
                    if (valorSeleccionado == relacion.id_opcion)
                        toDisplay.push("col" + relacion.id_campo);
                    //document.getElementById("col"+relacion.id_campo).style.display = "block";
                    else {
                        document.getElementById("col" + relacion.id_campo).style.display = "none";
                        limpiarRelacionesHijas(relacion.id_campo);
                        document.getElementById(relacion.id_campo).value = "";

                    }
                }
            });
            console.log("Display: ", toDisplay);

            toDisplay.forEach(function (elementID) {
                document.getElementById(elementID).style.display = "block";
            });
            //
            // Emilio - update
            //
            colorearFormulario();
        }

        function limpiarRelacionesHijas(id) {
            var valorSeleccionado = document.getElementById(id).value;

            arrayRelaciones.forEach(function (relacion) {
                if (id == relacion.id_campo_origen) {
                    if (valorSeleccionado == relacion.id_opcion) {
                        document.getElementById("col" + relacion.id_campo).style.display = "none";
                        document.getElementById(relacion.id_campo).value = "";
                    }
                }
            });

            //
            // Emilio - update
            //
            colorearFormulario();

        }


        function openRelatedFile(nodo) {
            console.log("abrir => ", $(nodo).attr("archivo"));
        }


        function sendForm() {
            /*
            var inputs = $("#ficha-form").find("input");
            $.each(inputs, function( key, value ){
                if(typeof $(value).attr("required")  !== "undefined"){
                    console.log($(value).val());
                }
            });
            */
            console.log($("#btnGuardar"), "sending...");
            $("#btnGuardar").trigger("click");
            //document.getElementById("ficha-form").submit(); 
        }

        var textareas = document.getElementsByTagName('textarea');
        for (var i = 0; i < textareas.length; i++) {
            textareas[i].addEventListener('keydown', function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    var start = this.selectionStart;
                    var end = this.selectionEnd;
                    var value = this.value;
                    this.value = value.substring(0, start) + '\n' + value.substring(end);
                    this.selectionStart = this.selectionEnd = start + 1;
                }
            }, false);
        }

    </script>
</body>

</html>