<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 10/06/2019
 * Time: 06:36 PM
 */
$GLOBALS['seleccion'] = $_POST['seleccion'];


require_once 'lib/funcionesEditor.php';
require_once 'lib/funcionesAdministrativas.php';
$filtroColecciones = obtenerColeccion(null);
require_once 'lib/funcionesSuperAdmin.php';
$filtroUsuarios = obtenerUsuarios();
$filtroMuseo = obtenerCampo(4);

if (isset($_POST['formSubmit'])) {
    $consulta = "";
    if (isset($_POST['colecciones'])) {
        $colecciones = $_POST['colecciones'];

        if (!empty($colecciones)) {
            $N = count($colecciones);

            for ($i = 0; $i < $N; $i++) {
                if ($i > 0)
                    $consulta .= " || ";
                $consulta .= "registros.ID_PERIODO = " . $colecciones[$i] . " ";
            }
        }
    }

    if (isset($_POST['museos'])) {
        $colecciones = $_POST['museos'];

        if (!empty($colecciones)) {
            $N = count($colecciones);
            if ($consulta !== "")
                $consulta .= " AND ";

            for ($i = 0; $i < $N; $i++) {
                if ($i > 0)
                    $consulta .= " || ";
                $consulta .= "registros.ID_MUSEO = " . $colecciones[$i] . " ";
            }
        }
    }

    if (isset($_POST['usuarios'])) {
        $usuariosSeleccionados = $_POST['usuarios'];

        if (!empty($usuariosSeleccionados)) {
            $N = count($usuariosSeleccionados);
            if ($consulta !== "")
                $consulta .= " AND ";

            for ($i = 0; $i < $N; $i++) {
                if ($i > 0)
                    $consulta .= " || ";
                $consulta .= "registros.ID_USUARIO = " . $usuariosSeleccionados[$i] . " ";
            }
        }
    }

    if ($consulta !== "") {
        $consulta = "AND " . $consulta;
        $registrosObtenidos = obtenerRegistrosFiltros($consulta, null, 1);
    } else
        $registrosObtenidos = obtenerRegistros(null, 1);

} else

    $registrosObtenidos = obtenerRegistros(null, 1);
?>



<?php
$registros = obtenerRegistrosDashboard($seleccion);

$generalCounters = array(
    "1" => 0,
    "2" => 0,
    "3" => 0,
    "4" => 0,
    "5" => 0,
    "6" => 0,
    "7" => 0,
    "8" => 0,
    "9" => 0,
    "10" => 0
);


foreach ($registros as $key => $registro) {
    if ($registro->{"ID_ESTATUS_PAPELERA"} == "2")
        unset($registros[$key]);
    else {




        //
        //$registro->{"numeroDeCat"} = ¿De donde lo obtenemos?
        //



        $registro->{"AUTOR"} = str_replace("\"", '\\"', $registro->{"AUTOR"});
        $registro->{"NUMERODECAT"} = $registro->{"NUMERODECAT"};



        $registro->{"MUSEO"} = obtenerValorDeId("cat_opciones", "ID", $registro->{"ID_MUSEO"}, "NOMBRE");
        $registro->{"COLECCION"} = obtenerValorDeId("periodos", "ID", $registro->{"ID_PERIODO"}, "NOMBRE");


        $generalCounters[strval($registro->{"ID_PERIODO"})] += 1;

        agregarCampos($registro->{"ID"}, $registro);

        $registro->{"nombreDeObra"} = str_replace("\"", '\\"', $registro->{"nombreDeObra"});


        $registro->{"FICHA"} = obtenerValorDeId("fichas", "ID", $registro->{"ID_FICHA"}, "NOMBRE");
        $registro->{"FECHA_CREACION"} = DateTime::createFromFormat('U.u', $registro->{"FECHA"})->format("d/m/Y");
        unset($registro->{"FECHA"});
        $registro->{"USUARIO_REGISTRO"} = obtenerValorDeId("usuarios", "ID", $registro->{"ID_USUARIO"}, "NOMBRE_COMPLETO");
        $registro->{"ACCIONES"} = array("VER");
        if (array_search(13, $global_permisos) !== false && $registro->{"ID_ESTATUS_REGISTRO"} != "3")
            array_push($registro->{"ACCIONES"}, "VALIDAR");
        if (array_search(6, $global_permisos) !== false)
            array_push($registro->{"ACCIONES"}, "ELIMINAR");
        if ($registro->{"ID_ESTATUS_REGISTRO"} == "3")
            array_push($registro->{"ACCIONES"}, "EDITAR");

        unset($registro->{"ID_ESTATUS_PAPELERA"});
    }
}
$registros = array_values($registros);








function obtenerRegistrosDashboard($seleccion)
{
    require_once 'utils/database_utils.php';
    $registrosEnRevision = getRowsFromDatabaseDash($seleccion, "2");
    $registrosValidados = getRowsFromDatabaseDash($seleccion, "3");
    $registrosCompletos = array_merge($registrosEnRevision, $registrosValidados);

    //$registrosCompletos = getRowsFromDatabaseDash($seleccion, "2");


    return $registrosCompletos;
}

function obtenerValorDeId($table, $keyFieldName, $keyFieldValue, $targetField)
{
    require_once 'utils/database_utils.php';
    $registro = getRowFromDatabase($table, $keyFieldValue, $keyFieldName);

    if ($targetField == "NOMBRE_COMPLETO") {
        return $registro->{"NOMBRE"} . " " . $registro->{"APELLIDOS"};
    } else
        return $registro->{$targetField};
}

function agregarCampos($nombre, $registro)
{
    $registro->{"nombreDeObra"} = "";
    $registro->{"inventario"} = "";
    $jsonContent = file_get_contents("reg/" . $nombre . ".json");
    $content = json_decode($jsonContent);
    foreach ($content as $seccion) {
        if ($seccion->{"nombreSeccion"} == "Objetos digitales" || $seccion->{"nombreSeccion"} == "Carga de objetos digitales") {
            $archivos = $seccion->{"archivos"};
            if (sizeof($archivos) > 0) {

                $current = pathinfo($archivos[0]);
                $thumb = $current["dirname"] . "/" . $current["filename"] . "_THUMB." . $current["extension"];

                $registro->{"thumb"} = $thumb;
            }
        }
        if ($seccion->{"nombreSeccion"} == "Campos obligatorios") {
            $camposTmp = $seccion->{"campos"};
            for ($i = 0; $i < sizeof($camposTmp); $i++) {
                $currentCampo = $camposTmp[$i];
                if ($currentCampo->{"idCampo"} == "3") {
                    $valor = str_replace(["\t", "\"", "\n"], "", $currentCampo->{"valor"});
                    $registro->{"inventario"} = $valor;
                }
            }
        }
        if ($seccion->{"nombreSeccion"} == "Identificación general" || $seccion->{"nombreSeccion"} == "Datos de identificación general") {
            $camposTmp = $seccion->{"campos"};
            for ($i = 0; $i < sizeof($camposTmp); $i++) {
                $currentCampo = $camposTmp[$i];
                if (in_array($currentCampo->{"idCampo"}, ["152", "746", "747", "748", "749", "750", "751", "752", "753"])) {
                    //if($currentCampo->{"idCampo"} == "152"){
                    $valor = str_replace(["\t", "\"", "\n"], "", $currentCampo->{"valor"});
                    $registro->{"nombreDeObra"} = $valor;
                }
            }
        }
        if ($seccion->{"nombreSeccion"} == "Datos técnicos") {
            $camposTmp = $seccion->{"campos"};
            for ($i = 0; $i < sizeof($camposTmp); $i++) {
                $currentCampo = $camposTmp[$i];
                if ($currentCampo->{"idCampo"} == "194") {
                    $valor = str_replace(["\t", "\n"], "", $currentCampo->{"valor"});
                    $registro->{"nombreDeObra"} = $valor;
                }
            }
        }
    }


}




?>

















<div class="custom-widgets">
    <div class="row-one">

        <!--
                        <div class="col-md-3 widget">
                            <div class="stats-left ">
                                <h5>Colección</h5>
                                <h4> Paleontológico</h4>
                            </div>
                            <div class="stats-right">
                                <label><?php echo $generalCounters["1"]; ?></label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        -->
        <div class="col-md-3 widget">
            <div class="stats-left">
                <h5>Colección</h5>
                <h4>Arqueológico</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["10"]; ?>
                </label>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-3 widget states-mdl">
            <div class="stats-left">
                <h5>Colección</h5>
                <h4>Virreinal</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["3"]; ?>
                </label>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-3 widget states-thrd">
            <div class="stats-left">
                <h5>Colección</h5>
                <h4>Arte moderno</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["4"]; ?>
                </label>
            </div>
            <div class="clearfix"> </div>
        </div>


        <div class="col-md-3 widget states-last">
            <div class="stats-left ">
                <h5>Específicas</h5>
                <h4>Arte popular</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["5"]; ?>
                </label>
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
            <div class="stats-left">
                <h5>Específicas</h5>
                <h4>Numismática</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["6"]; ?>
                </label>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-3 widget states-six">
            <div class="stats-left">
                <h5>Específicas</h5>
                <h4>Historia natural</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["7"]; ?>
                </label>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-3 widget states-seven">
            <div class="stats-left">
                <h5>Colección</h5>
                <h4>Objeto histórico</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["8"]; ?>
                </label>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="col-md-3 widget states-eight">
            <div class="stats-left ">
                <h5>Colección</h5>
                <h4>Siglo XIX</h4>
            </div>
            <div class="stats-right">
                <label>
                    <?php echo $generalCounters["9"]; ?>
                </label>
            </div>
            <div class="clearfix"> </div>
        </div>





        <div class="clearfix"> </div>
    </div>
</div><br>


<div class="candile">
    <div class="candile-inner">























        <div class="facetador">
            <div class="title_facetador">
                <div id="hamburger-button-container"></div>
                <h4>Filtrar</h4>
            </div>
            <div class="row_main mb40">
                <form action="main.php?mr=5" method="post">
                    <div class="col-md-3">
                        <div class="filtro">
                            <div class="titulo_filtro">
                                Colección
                            </div>




                            <ul class="filtro__contenido">
                                <li>
                                    <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="16"
                                        onclick="activarFiltroFicha(this)" />
                                    <label>Arqueológico</label>
                                </li>
                                <li>
                                    <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="6"
                                        onclick="activarFiltroFicha(this)" />
                                    <label>Virreinato</label>
                                </li>
                                <li>
                                    <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="7"
                                        onclick="activarFiltroFicha(this)" />
                                    <label>Arte Moderno y Contemporaneo</label>
                                </li>
                                <li>
                                    <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="8"
                                        onclick="activarFiltroFicha(this)" />
                                    <label>Culturas Populares</label>
                                </li>
                                <li>
                                    <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="9"
                                        onclick="activarFiltroFicha(this)" />
                                    <label>Numismática</label>
                                </li>
                                <li>
                                    <label>Historia Natural</label>
                                    <ul class="sub-ficha">
                                        <li>
                                            <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-1"
                                                id="10" onclick="activarFiltroFicha(this)" />
                                            <label>Rocas y Minerales</label>
                                        </li>
                                        <li>
                                            <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-2"
                                                id="11" onclick="activarFiltroFicha(this)" />
                                            <label>Osteología</label>
                                        </li>
                                        <li>
                                            <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3"
                                                id="12" onclick="activarFiltroFicha(this)" />
                                            <label>Taxidermia</label>
                                        </li>
                                        <li>
                                            <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3"
                                                id="13" onclick="activarFiltroFicha(this)" />
                                            <label>Paleontología</label>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="14"
                                        onclick="activarFiltroFicha(this)" />
                                    <label>Objeto Histórico</label>
                                </li>
                                <li>
                                    <input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="15"
                                        onclick="activarFiltroFicha(this)" />
                                    <label>Arte del siglo XIX</label>
                                </li>
                            </ul>




                        </div><!--Termina filtro-->
                    </div><!--Termina col-md-4-->
                    <div class="col-md-3">
                        <div class="filtro museos">
                            <div class="titulo_filtro">
                                Museo
                            </div>
                            <ul class="filtro__contenido">
                            </ul><!--Termina ul filtro_contenido-->
                        </div><!--Termina filtro-->
                    </div>
                    <div class="col-md-3">
                        <div class="filtro usuarios">
                            <div class="titulo_filtro">
                                Usuario de registro
                            </div>
                            <ul class="filtro__contenido">

                            </ul><!--Termina ul filtro_contenido-->
                        </div><!--Termina filtro-->
                    </div>
                    <div class="col-md-3">
                        <div class="filtro autores">
                            <div class="titulo_filtro">
                                Autor
                            </div>
                            <ul class="filtro__contenido">

                            </ul><!--Termina ul filtro_contenido-->
                        </div><!--Termina filtro-->
                    </div>
                    <div style="clear:both"></div>
                    <button class="btn-registros" id="generadorDeReportes" onclick="generarReporteExcel()"
                        style="display:none;">Generar reporte</button>
                    <!--<input type="submit" name="formSubmit" value="filtroDashboard" /> -->
                </form>
            </div><!--Termina row mb40-->

        </div><!--Termina facetador-->

        <div class="buscar" style="float: left;">
            <form>
                <input type="text" value="" class="v2" placeholder="Buscar" onkeyup="buscarPorFolio(this.value)">
            </form>
        </div>
        <div class="dataTables_length">
            <label>Mostrar
                <select name="example_length" aria-controls="example" class="" onchange="cambiarLongitudDeTabla(this)">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                registros
            </label>
        </div>
        <div class="card-body " id="bar-parent">
            <table id="exportTable" class="table table-bordered" style="width:100%">
                <input type="hidden" id="currentUserId" value="<?php echo $idUsuario; ?>" />
                <thead class="principal">
                    <tr>
                        <th></th>
                        <th style="display:none;">Folio</th>

                        <th>Imagen</th>
                        <th>Título / Nombre</th>
                        <th>Autor</th>
                        <th>Museo</th>
                        <th>Colección</th>
                        <th>Inventario</th>
                        <th style="width:10%;">Estatus</th>
                        <th style="width:15%;">Acciones</th>
                        <th>Usuario</th>


                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="10">
                            <div class="pagination table">

                            </div>
                    </tr>
                </tfoot>
                <tbody>



                    <tr></tr>
            </table>

            <script>






                var registrosJSON = JSON.parse('<?php echo json_encode($registros, JSON_UNESCAPED_UNICODE); ?>');

                var registrosActivos = [...registrosJSON];

                var registrosVisibles = [...registrosJSON];
                var completeList = true;

                var filtrosDeUsuarios;
                var filtrosDeMuseos;


                var filtrosDeAutores;



                //Constantes de control
                var fichaActiva = null;
                var museoActivo = null;
                var usuarioActivo = null;

                var autorActivo = null



                var paginaActiva = 1;
                var registrosPorPagina = 10;


                filtrarRegistros();




                var fichasParaReporte = [];



                function cambiarLongitudDeTabla(node) {
                    paginaActiva = 1;
                    registrosPorPagina = node.value;
                    filtrarRegistros();
                }

                function paginar(elementos) {
                    var paginador = $(".pagination").first();
                    paginador.empty();
                    var contador = Math.floor(elementos.length / registrosPorPagina);
                    if ((elementos % registrosPorPagina) != 0)
                        contador += 1;
                    paginador.append('<button onclick="cambiarPaginaAnterior(' + contador + ')">&laquo;</button>');

                    /*
                    for(var i = 0 ; i < contador ; i++){
                        var activa = "";
                        if((i+1) == paginaActiva )
                            activa = "active";
                        paginador.append('<button class="' + activa + '" onclick="cambiarPagina(' + (i+1) + ')">' + (i+1) + '</button>');
                    }
                    */

                    paginador.append('<button>' + paginaActiva + ' de ' + contador + '</button>');
                    paginador.append('<button onclick="cambiarPaginaSiguiente(' + contador + ')">&raquo;</button>');

                    var min = registrosPorPagina * (paginaActiva - 1);
                    var max = registrosPorPagina * paginaActiva;
                    return elementos.slice(min, max);
                }

                function cambiarPaginaSiguiente(contadorPaginas) {
                    if (contadorPaginas > 1 && paginaActiva < contadorPaginas) {
                        paginaActiva++;
                        poblarTablaDeRegistros(paginar(registrosActivos));
                        //filtrarRegistros();
                    }
                }

                function cambiarPaginaAnterior(contadorPaginas) {
                    if (contadorPaginas > 1 && paginaActiva > 1) {
                        paginaActiva--;
                        poblarTablaDeRegistros(paginar(registrosActivos));
                        //filtrarRegistros();
                    }
                }

                function cambiarPagina(target) {
                    paginaActiva = target;
                    poblarTablaDeRegistros(paginar(registrosActivos));
                    //filtrarRegistros();
                }

                function activarFiltroFicha(selected) {
                    $(".facetador-ficha").prop('checked', false);

                    $(selected).prop('checked', true);
                    fichaActiva = $(selected).attr('id')
                    $("#generadorDeReportes").fadeIn();
                    filtrarRegistros();
                    $("table > tbody > tr > td:nth-of-type(1) > input").removeAttr("disabled");
                }

                function generarReporteExcel() {
                    var idDeFicha = $('input.facetadores[type="radio"]:checked').attr('id');
                    window.open('generadorDeReporteEspecifico.php?ficha=' + idDeFicha + '&registros=' + obtenerFichasParaReporte(), '_blank');
                }

                function obtenerFichasParaReporte() {
                    if (fichasParaReporte.length == 0) {
                        var datas = $("table > tbody > tr > td:nth-of-type(2)");
                        var ids = [];
                        $.each(datas, function (index, element) {
                            ids.push(element.textContent);
                        });
                        return JSON.stringify(ids);
                    } else {
                        return JSON.stringify(fichasParaReporte);
                    }
                }

                function filtrarRegistros() {
                    var registros = [...registrosVisibles];
                    /*registros.sort(function (a, b) {
                        var key1 = parseFloat(a.ID.split("-")[1]);
                        var key2 = parseFloat(b.ID.split("-")[1]);
                        if (key1 > key2)
                            return -1;
                        if (key1 < key2)
                            return 1;
                        return 0;
                    });*/
                    /*registros.sort(function (a, b) {
                        var key1 = parseFloat(a.ID.split("")[1]);
                        var key2 = parseFloat(b.ID.split("-")[1]);

                        // Verificar si los valores no son números válidos
                        if (isNaN(key1) || isNaN(key2)) {
                            // Tratar los valores no numéricos como iguales
                            return 0;
                        }

                        if (key1 > key2) {
                            return -1;
                        } else if (key1 < key2) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });*/   
                    fichasParaReporte = [];
                    if (fichaActiva != null) {
                        //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                        for (var i = 0; i < registros.length; i++) {
                            if (registros[i].ID_FICHA != fichaActiva) {
                                registros.splice(i, 1);
                                i--;
                            }
                        }

                    }
                    if (museoActivo != null) {
                        //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                        for (var i = 0; i < registros.length; i++) {
                            if (registros[i].ID_MUSEO != museoActivo) {
                                registros.splice(i, 1);
                                i--;
                            }
                        }
                    }
                    if (usuarioActivo != null) {
                        //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                        for (var i = 0; i < registros.length; i++) {
                            if (registros[i].ID_USUARIO != usuarioActivo) {
                                registros.splice(i, 1);
                                i--;
                            }
                        }
                    }





                    if (autorActivo != null) {
                        //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                        for (var i = 0; i < registros.length; i++) {
                            if (registros[i].AUTOR != autorActivo) {
                                registros.splice(i, 1);
                                i--;
                            }
                        }
                    }

                    filtrosDeUsuarios = [];
                    filtrosDeMuseos = [];
                    filtrosDeFichas = [];
                    filtrosDeAutores = [];

                    $.each(registros, function (index, registro) {
                        if (!findObject(registro.MUSEO, filtrosDeMuseos))
                            filtrosDeMuseos.push({ nombre: registro.MUSEO, id: registro.ID_MUSEO });
                        if (!findObject(registro.USUARIO_REGISTRO, filtrosDeUsuarios))
                            filtrosDeUsuarios.push({ nombre: registro.USUARIO_REGISTRO, id: registro.ID_USUARIO });
                        if (!findObject(registro.FICHA, filtrosDeFichas))
                            filtrosDeFichas.push({ nombre: registro.FICHA, id: registro.ID_FICHA });

                        if (!findObject(registro.AUTOR, filtrosDeAutores))
                            filtrosDeAutores.push({ nombre: registro.AUTOR, id: registro.AUTOR });
                    });

                    $(".filtro.usuarios > .filtro__contenido").empty();
                    $(".filtro.museos > .filtro__contenido").empty();
                    $(".filtro.autores > .filtro__contenido").empty();

                    construirFiltrosDeMuseos(filtrosDeMuseos);
                    construirFiltrosDeUsuarios(filtrosDeUsuarios);
                    configurarFiltrosDeAutores(filtrosDeAutores);

                    registrosActivos = [...registros];

                    poblarTablaDeRegistros(paginar(registros));
                    if (fichaActiva != null)
                        $("table > tbody > tr > td:nth-of-type(1) > input").removeAttr("disabled");

                }

                function agregarFichaAReporte(nodo, idDeFicha) {
                    if ($(nodo).prop("checked")) {
                        fichasParaReporte.push(idDeFicha);
                    } else {
                        //Eliminar
                        for (var i = 0; i < fichasParaReporte.length; i++) {
                            if (fichasParaReporte[i] === idDeFicha) {
                                fichasParaReporte.splice(i, 1);
                                i--;
                            }
                        }
                    }
                    console.log(fichasParaReporte);
                }

                function poblarTablaDeRegistros(registros) {
                    //registrosVisibles = registros;

                    console.log("procesando: ", registros);

                    //Vaciar contenedor de registros
                    var contenedorDeRegistros = $("#exportTable > tbody").first();
                    contenedorDeRegistros.empty();





                    $.each(registros, function (index, registro) {
                        console.log("processing => ", registro);
                        //var enReporte = (fichasParaReporte.includes(registro.ID)) ? ""
                        var registroHTML = '<tr><td><input type="checkbox" disabled onclick="agregarFichaAReporte(this, \'' + registro.ID + '\')"></td>';
                        registroHTML += '<td style="display:none;"><input style="visibility: hidden; width: 1%; height: 1%;" value="' + registro.ID + '" name="folio" readonly="">' + registro.ID + '</td>';

                        registroHTML += (registro.thumb != undefined ? '<td><img style="height: 80px;" src="' + registro.thumb + '"/></td>' : '<td></td>');

                        registroHTML += '<td>' + registro.nombreDeObra + '</td>';

                        if (registro.AUTOR == "") {
                            registro.AUTOR = "Desconocido";
                        }
                        registroHTML += '<td>' + registro.AUTOR + '</td>';
                        registroHTML += '<td>' + registro.MUSEO + '</td>';
                        registroHTML += '<td>' + registro.COLECCION + '</td>';
                        registroHTML += '<td>' + registro.inventario + '</td>';

                        registroHTML += '<td><span class="label label-sm label-success">' + ((registro.ID_ESTATUS_REGISTRO == 2) ? "Revisión" : "Validado") + '</span></td>';
                        var botonValidar = (registro.ACCIONES.includes('VALIDAR')) ? '<a href="#"><button class="btn btn-success btn-xs" name="btnValidar"><i class="fa fa-check"></i></button></a>' : "";
                        var botonEliminar = (registro.ACCIONES.includes('ELIMINAR')) ? '<a href="#"><button class="btn btn-papelera btn-xs" name="btnPapelera"><i class="fa fa-trash-o"></i></button></a>' : "";
                        var botonEditar = (registro.ACCIONES.includes("EDITAR")) ? '<a href="main.php?mr=2&amp;fi=' + registro.ID + '&amp;f=' + registro.ID_FICHA + '&amp;p=' + registro.ID_PERIODO + '"><button class="btn btn-editar btn-xs"><i class="fa fa-pencil"></i></button></a>' : '';
                        var inputFields = '<input type="hidden" value="' + registro.ID + '" name="folio" readonly=""><input type="hidden" value="' + $("#currentUserId").val() + '" name="usuario" readonly=""><input type="hidden" value="' + registro.ID_USUARIO + '" name="usuarioDestino" readonly=""><input type="hidden" value="' + registro.ID_PERIODO + '" name="coleccion" readonly="">';
                        //registroHTML += '<td><div class="accnstbl"><a href="main.php?mr=25&amp;idr=' + registro.ID + '"><button class="btn btn-editar btn-xs"><i class="fa fa-eye"></i></button></a>' + botonEditar + '<form role="form" action="accionRegistro.php" method="post">' + inputFields + botonValidar + botonEliminar + '</form></div></td>';
                        registroHTML += '<td><div class="accnstbl"><a href="main.php?mr=25&amp;idr=' + registro.ID + '" target="_blank"><button class="btn btn-editar btn-xs"><i class="fa fa-eye"></i></button></a>' + botonEditar + '<form role="form" action="accionRegistro.php" method="post">' + inputFields + botonValidar + botonEliminar + '</form></div></td>';


                        registroHTML += '<td>' + registro.USUARIO_REGISTRO + '</td>';
                        registroHTML += '</tr>';
                        contenedorDeRegistros.append(registroHTML);
                    });
                }

                function findObject(name, array) {
                    var found = false;
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].nombre == name) {
                            found = true;
                            break;
                        }
                    }
                    return found;
                }

                function construirFiltrosDeUsuarios(usuarios) {
                    usuarios.forEach(function (usuario) {
                        var string = '<li><input type="checkbox" id="" name="usuarios[]" value="' + usuario.id + '" class="to-fa facetadores" onclick="accionarFiltroDeUsuario(this)"> <label> ' + usuario.nombre + '</label></li>';
                        $(".filtro.usuarios > .filtro__contenido").append(string);
                    });
                    if (usuarioActivo != null) {
                        $(".filtro.usuarios > .filtro__contenido > li > input").prop("checked", true);
                    }
                }

                function configurarFiltrosDeFichas(fichas) {
                    $(".facetador-ficha").attr('disabled', true);
                    fichas.forEach(function (ficha) {
                        console.log("activando(.facetador-ficha[value='" + ficha.id + "']) => " + ficha.nombre);
                        $(".facetador-ficha[id='" + ficha.id + "']").attr('disabled', false);
                    });
                }

                function configurarFiltrosDeAutores(autores) {
                    autores.forEach(function (autor) {
                        var string = '<li><input type="checkbox" id="" name="autores[]" value="' + autor.id + '" class="to-fa facetadores" onclick="accionarFiltroDeAutor(this)"> <label> ' + autor.nombre + '</label></li>';
                        $(".filtro.autores > .filtro__contenido").append(string);
                    });
                    if (autorActivo != null) {
                        $(".filtro.autores > .filtro__contenido > li > input").prop("checked", true);
                    }
                }

                function construirFiltrosDeMuseos(museos) {
                    museos.forEach(function (museo) {
                        var string = '<li><input type="checkbox" id="" name="museos[]" value="' + museo.id + '" class="to-fa facetadores" onclick="accionarFiltroDeMuseo(this)"> <label> ' + museo.nombre + '</label></li>';
                        $(".filtro.museos > .filtro__contenido").append(string);
                    });
                    if (museoActivo != null) {
                        $(".filtro.museos > .filtro__contenido > li > input").prop("checked", true);
                    }
                }

                function accionarFiltroDeUsuario(filtro) {
                    if ($(filtro).prop('checked')) {
                        //fue activado...
                        usuarioActivo = $(filtro).val();
                    } else {
                        usuarioActivo = null;
                    }
                    filtrarRegistros();
                }

                function accionarFiltroDeMuseo(filtro) {
                    if ($(filtro).prop('checked')) {
                        //fue activado...
                        museoActivo = $(filtro).val();
                    } else {
                        museoActivo = null;
                    }
                    filtrarRegistros();
                }

                function accionarFiltroDeAutor(filtro) {
                    if ($(filtro).prop('checked')) {
                        //fue activado...
                        autorActivo = $(filtro).val();
                    } else {
                        autorActivo = null;
                    }
                    filtrarRegistros();
                }

                function buscarPorFolio(string) {
                    var filtrados = [];
                    paginaActiva = 1;
                    if (string.length > 1) {
                        $.each(registrosVisibles, function (index, registro) {
                            if (registro.ID.toLowerCase().includes(string.toLowerCase()) || registro.AUTOR.toLowerCase().includes(string.toLowerCase()) || registro.inventario.toLowerCase().includes(string.toLowerCase()) || registro.nombreDeObra.toLowerCase().includes(string.toLowerCase()))
                                filtrados.push(registro);
                        });

                        registrosVisibles = filtrados;
                        filtrarRegistros();
                        registrosVisibles = [...registrosJSON];
                        completeList = false;
                    } else {
                        if (!completeList) {
                            filtrarRegistros();
                            completeList = true;
                        }
                    }
                }
            </script>

        </div><!--//card-body-->