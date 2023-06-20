<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 10/06/2019
 * Time: 06:36 PM
 */

$filtroColecciones = obtenerColeccion(null);
$filtroMuseo = obtenerCampo(4);

if(isset($_POST['formMisRegistros'])) {
    $consulta="";
    if(isset($_POST['colecciones'])) {
        $colecciones = $_POST['colecciones'];

        if (!empty($colecciones)) {
            $N = count($colecciones);

            for ($i = 0; $i < $N; $i++) {
                if($i>0)
                    $consulta.=" || ";
                $consulta.="registros.ID_PERIODO = ".$colecciones[$i]." ";
            }
        }
    }

    if(isset($_POST['museos'])) {
        $colecciones = $_POST['museos'];

        if (!empty($colecciones)) {
            $N = count($colecciones);
            if($consulta!=="")
                $consulta.=" AND ";

            for ($i = 0; $i < $N; $i++) {
                if($i>0)
                    $consulta.=" || ";
                $consulta.="registros.ID_MUSEO = ".$colecciones[$i]." ";
            }
        }
    }


    if($consulta!=="") {
        $consulta=" AND ".$consulta;
        $registrosObtenidos = obtenerRegistrosFiltros($consulta,$idUsuario, 1);
    }
    else
        $registrosObtenidos = obtenerRegistros($idUsuario,1);

}else
    $registrosObtenidos = obtenerRegistros($idUsuario,1);


?>

<div class="facetador">
    <div class="title_facetador">
        <div id="hamburger-button-container"></div>
        <h4>Filtrar</h4>
    </div>
    <div class="row_main mb40">
        <form action="main.php?mr=1" method="post" >
        <div class="col-md-4">
            <div class="filtro">
                <div class="titulo_filtro">
                    Colección
                </div>
                <ul class="filtro__contenido">
	<li>
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="16" onclick="activarFiltroFicha(this)"/>
		<label>Arqueológico</label>
	</li>
	<li>
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="6" onclick="activarFiltroFicha(this)"/>
		<label>Virreinato</label>
	</li>
	<li>
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="7" onclick="activarFiltroFicha(this)"/>
		<label>Arte Moderno y Contemporaneo</label>
	</li>
	<li>
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="8" onclick="activarFiltroFicha(this)"/>
		<label>Culturas Populares</label>
	</li>
	<li>
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="9" onclick="activarFiltroFicha(this)"/>
		<label>Numismática</label>
	</li>
	<li>
		<label>Historia Natural</label>
		<ul class="sub-ficha">
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-1" id="10" onclick="activarFiltroFicha(this)"/>
				<label>Rocas y Minerales</label>
			</li>
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-2" id="11" onclick="activarFiltroFicha(this)"/>
				<label>Osteología</label>
			</li>
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="12" onclick="activarFiltroFicha(this)"/>
				<label>Taxidermia</label>
			</li>
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="13" onclick="activarFiltroFicha(this)"/>
				<label>Paleontología</label>
			</li>
		</ul>
	</li>
	<li>
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="14" onclick="activarFiltroFicha(this)"/>
		<label>Objeto Histórico</label>
	</li>
	<li>
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="15" onclick="activarFiltroFicha(this)"/>
		<label>Arte del siglo XIX</label>
	</li>
</ul>
            </div><!--Termina filtro-->
        </div><!--Termina col-md-4-->
        <div class="col-md-4">
            <div class="filtro museos">
                <div class="titulo_filtro">
                    Museo
                </div>
                
                
                <ul class="filtro__contenido">
                </ul><!--Termina ul filtro_contenido-->


                
                
            </div><!--Termina filtro-->
        </div>
        
        
        
        
        <div class="col-md-4">
            <div class="filtro autores">
                <div class="titulo_filtro">
                    Autor
                </div>
                <ul class="filtro__contenido">
                    
                </ul><!--Termina ul filtro_contenido-->
            </div><!--Termina filtro-->
        </div>
        
        
        
        
        <div style="clear:both"></div>
            <button id="generadorDeReportes" onclick="generarReporteExcel()" style="display:none;" class="btn-registros">Generar reporte</button>
        </form>
    </div><!--Termina row mb40-->
    
    
    
    
    
    
    
    <script>
    
        function activarFiltroFicha(selected){
            $(".facetador-ficha").prop('checked',false);
            $(selected).prop('checked',true);
            fichaSeleccionada = $(selected).attr('id')
            $("#generadorDeReportes").fadeIn();
            filtrarRegistros();
            $("table > tbody > tr > td:nth-of-type(1) > input").removeAttr("disabled");
        }
        
        function generarReporteExcel(){
            var idDeFicha = $('input.facetadores[type="radio"]:checked').attr('id');
            window.open('generadorDeReporteEspecifico.php?ficha=' + idDeFicha + '&registros=' + obtenerFichasParaReporte(), '_blank');
        }
        
        function obtenerFichasParaReporte(){
            if(fichasParaReporte.length == 0){
                var datas = $("table > tbody > tr > td:nth-of-type(2)");
                var ids = [];
                $.each(datas, function(index, element){
                    ids.push(element.textContent);
                });
                return JSON.stringify(ids);
            } else {
                return JSON.stringify(fichasParaReporte);
            }
        }
        
    </script>
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        <?php
            $registros = obtenerMisRegistros($idUsuario);
            
            
            
            
            foreach($registros as $key => $registro){
                if($registro->{"ID_ESTATUS_PAPELERA"} == "2")
                    unset($registros[$key]);
                else {

                    agregarCampos($registro->{"ID"}, $registro);
                    
                    $registro->{"nombreDeObra"} = str_replace("\"", '\\"', $registro->{"nombreDeObra"});
                
                    $registro->{"AUTOR"} = str_replace("\"", '\\"', $registro->{"AUTOR"});
                    $registro->{"NUMERODECAT"} = $registro->{"NUMERODECAT"};
                    
                    $registro->{"MUSEO"} = obtenerValorDeId("cat_opciones", "ID", $registro->{"ID_MUSEO"}, "NOMBRE");
                    $registro->{"COLECCION"} = obtenerValorDeId("periodos", "ID", $registro->{"ID_PERIODO"}, "NOMBRE");
                    $registro->{"FICHA"} = obtenerValorDeId("fichas", "ID", $registro->{"ID_FICHA"}, "NOMBRE");
                    $registro->{"FECHA_CREACION"} = DateTime::createFromFormat('U.u', $registro->{"FECHA"})->format("d/m/Y");
                    $registro->{"ESTATUS"} = obtenerValorDeId("cat_estatus_registros", "ID", $registro->{"ID_ESTATUS_REGISTRO"}, "NOMBRE");
                    unset($registro->{"FECHA"});
                    $registro->{"USUARIO_REGISTRO"} = obtenerValorDeId("usuarios", "ID", $registro->{"ID_USUARIO"}, "NOMBRE_COMPLETO");
                    $registro->{"ACCIONES"} = array("VER");
                    switch($registro->{"ID_ESTATUS_REGISTRO"}){
                        case "1":
                            array_push($registro->{"ACCIONES"}, "EDITAR", "VALIDAR", "ELIMINAR");
                            break;
                        case "5":
                            array_push($registro->{"ACCIONES"}, "EDITAR", "VALIDAR");
                            break;
                        case "3":
                            array_push($registro->{"ACCIONES"}, "EDITAR", "ELIMINAR");
                            break;
                    }
                        
                }
            }
            
            
            $registros = array_values($registros);
            
            
            /*
            foreach( $registros as $registro){
                var_dump($registro);
                echo "<br/><br/>";
            }
            var_dump(json_encode((array)$registros, JSON_UNESCAPED_UNICODE ));
            */
            
            function obtenerMisRegistros($id){
                require_once 'utils/database_utils.php';
                $registros = getRowsFromDatabase("registros", $id, "ID_USUARIO");
                return $registros;
            }
            
            function obtenerValorDeId($table, $keyFieldName, $keyFieldValue, $targetField){
                require_once 'utils/database_utils.php';
                $registro = getRowFromDatabase($table, $keyFieldValue, $keyFieldName);
                
                if($targetField == "NOMBRE_COMPLETO"){
                    return $registro->{"NOMBRE"} . " " . $registro->{"APELLIDOS"};
                } else 
                    return $registro->{$targetField};
            }
            
            
            
            
            
            
            
            function agregarCampos($nombre, $registro){
                $registro->{"nombreDeObra"} = "";
                $registro->{"inventario"} = "";
                $jsonContent = file_get_contents("reg/" . $nombre . ".json");
                $content = json_decode($jsonContent);
                foreach($content as $seccion){
                    if($seccion->{"nombreSeccion"} == "Objetos digitales" || $seccion->{"nombreSeccion"} == "Carga de objetos digitales"){
                        $archivos = $seccion->{"archivos"};
                        if(sizeof($archivos) > 0){
                            $current = pathinfo($archivos[0]);
                            $thumb = $current["dirname"] . "/" . $current["filename"] . "_THUMB." . $current["extension"];
                            $registro->{"thumb"} = $thumb;
                        }
                    } else if ($seccion->{"nombreSeccion"} == "Campos obligatorios"){
                        $camposTmp = $seccion->{"campos"};
                        for($i = 0 ; $i < sizeof($camposTmp) ; $i++){
                            $currentCampo = $camposTmp[$i];
                            if($currentCampo->{"idCampo"} == "3"){
                                $valor = str_replace(["\t", "\"", "\n"], "", $currentCampo->{"valor"});
                                $registro->{"inventario"} = $valor;
                            }
                        }
                    } else if ($seccion->{"nombreSeccion"} == "Identificación general" || $seccion->{"nombreSeccion"} == "Datos de identificación general") {
                        $camposTmp = $seccion->{"campos"};
                        for($i = 0 ; $i < sizeof($camposTmp) ; $i++){
                            $currentCampo = $camposTmp[$i];
                            if (in_array($currentCampo->{"idCampo"}, ["152", "746", "747", "748", "749", "750", "751", "752", "753"])){
                            //if($currentCampo->{"idCampo"} == "152"){
                                $valor = str_replace(["\t", "\"", "\n"], "", $currentCampo->{"valor"});
                                $registro->{"nombreDeObra"} = $valor;
                            }
                        }
                    } else if ($seccion->{"nombreSeccion"} == "Datos técnicos") {
                        $camposTmp = $seccion->{"campos"};
                        for($i = 0 ; $i < sizeof($camposTmp) ; $i++){
                            $currentCampo = $camposTmp[$i];
                            if($currentCampo->{"idCampo"} == "194"){
                                $valor = str_replace(["\t", "\n"], "", $currentCampo->{"valor"});
                                $registro->{"nombreDeObra"} = $valor;
                            }
                        }
                    }
                }
            }
            
            
            
            ?>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <table id="exportTable" class="table table-bordered" style="width:100%">
        <input type="hidden" id="currentUserId" value="<?php echo $idUsuario; ?>"/>
        <thead class="principal">
        <tr>
            
            <!--
            <th></th>
            <th>Folio</th>
            <th style="width:10%;">Estatus</th>
            <th style="width:15%;">Acciones</th>
            <th>Fecha de registro</th>
            <th>Usuario de registro</th>
            <th>Colección</th>
            <th>Ficha</th>
            <th>Autor</th>
            -->
            
            <th></th>
            <th style="display:none;">Folio</th>
            
            <th>Imagen</th>
            <th>Título/Nombre</th>
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
        <?php
        foreach ($registrosObtenidos as $registro)
        {
        ?>
        <tr>
            <td>
                <input type="checkbox"></input>
            </td>
            <td><?php echo $registro->folio; ?></td>
            <td>
                <span class="label label-sm label-success"><?php echo $registro->estatus; ?></span>
            </td>
            <td>
                <div class="accnstbl">
                    <!--<a href="main.php?mr=2&fi=<?php echo $registro->folio; ?>&f=<?php echo $registro->id_ficha; ?>">-->
                    <a href="main.php?mr=25&idr=<?php echo $registro->folio; ?>">
                        <button class="btn btn-editar btn-xs">
                            <i class="fa fa-eye"></i>
                        </button>
                    </a>
                    <?php
                    //if($registro->id_estatus==1 || $registro->id_estatus == 5 ){
                    ?>
                    <a href="main.php?mr=2&fi=<?php echo $registro->folio; ?>&f=<?php echo $registro->id_ficha; ?>&p=<?php echo $registro->id_periodo; ?>">
                        <button class="btn btn-editar btn-xs">
                            <i class="fa fa-pencil"></i>
                        </button>
                    </a>
                        <?php
                    //}
                    ?>
                    <form role="form" action="accionRegistro.php" method="post">
                        <input style="visibility: hidden; width: 1%; height: 1%;"
                               value="<?php echo $registro->folio; ?>" name="folio" readonly>
                        <input style="visibility: hidden; width: 1%; height: 1%;"
                               value="<?php echo $idUsuario; ?>" name="usuario" readonly>
                        <input style="visibility: hidden; width: 1%; height: 1%;"
                                   value="<?php echo $registro->id_usuario; ?>" name="usuarioDestino" readonly>
                        <input style="visibility: hidden; width: 1%; height: 1%;"
                               value="<?php echo $registro->id_periodo; ?>" name="coleccion" readonly>
                        <?php
                        if($registro->id_estatus==1 || $registro->id_estatus == 5){
                        ?>

                        <a href="#">
                            <button class="btn btn-success btn-xs" name="btnRevision">
                                <i class="fa fa-check"></i>
                            </button>
                        </a>
                        <?php
                        }
                        if(($registro->id_estatus==1 && $rol!=1)||($registro->id_estatus!=1 && $rol!=1) ||($registro->id_estatus==1 && $rol==1)) {
                            ?>
                            <a href="#">
                                <button class="btn btn-papelera btn-xs" name="btnPapelera">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </a>
                            <?php
                        }
                        ?>
                        </form>
                    </div>
                </td>
                <td><?php echo $registro->fecha;?></td>
                <td><?php echo $registro->nombre." ".$registro->apellidos;?></td>
                <td><?php echo $registro->periodo;?></td>
                <td><?php echo $registro->ficha;?></td>

            </tr>
            <?php
        }
        ?>
        <tr ></tr>
    </table>
    
    
    <script>
        
        //Registros actuales
        var registrosActuales = JSON.parse('<?php echo json_encode($registros, JSON_UNESCAPED_UNICODE ); ?>');
        var registrosActivos = [...registrosActuales];
        var registrosVisibles  = [...registrosActuales];
        var completeList = true;
        
        var fichasParaReporte = [];
        
        //contenedores de filtros lógicos
        var museoSeleccionado = null;
        var fichaSeleccionada = null
        var autorSeleccionado = null;
        
        //Contenedores lógicos de filtros
        var filtrosDeMuseos;
        var filtrosDeAutores;
        
        
        var paginaActiva = 1;
        var registrosPorPagina = 10;
        
        
        filtrarRegistros();

        
        
        
        function filtrarRegistros(){
            var registros = [...registrosVisibles];
            
            registros.sort(function (a, b) {
                var key1 = parseFloat(a.ID.split("-")[1]);
                var key2 = parseFloat(b.ID.split("-")[1]);
                if (key1 > key2)
                    return -1;
                if (key1 < key2)
                    return 1;
                return 0;
            });
            
            fichasParaReporte = [];
            if(fichaSeleccionada != null){
                //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                for( var i = 0; i < registros.length; i++){ 
                    if ( registros[i].ID_FICHA != fichaSeleccionada) {
                        registros.splice(i, 1);
                        i--;
                    }
                }
            }
            if(museoSeleccionado != null){
                //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                for( var i = 0; i < registros.length; i++){ 
                    if ( registros[i].ID_MUSEO != museoSeleccionado) {
                        registros.splice(i, 1);
                        i--;
                    }
                }
            }
            
            if(autorSeleccionado != null){
                //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                for( var i = 0; i < registros.length; i++){ 
                    if ( registros[i].AUTOR != autorSeleccionado) {
                        registros.splice(i, 1);
                        i--;
                    }
                }
            }
            
            
            //Vaciar contenedores de filtros...
            $(".filtro.museos > .filtro__contenido").empty();
            $(".filtro.autores > .filtro__contenido").empty();
            
            //Contenedores lógicos de filtros
            filtrosDeMuseos = [];
            filtrosDeAutores = [];
            
            $.each(registros, function(index, registro){
                if(!findObject(registro.MUSEO, filtrosDeMuseos))
                    filtrosDeMuseos.push({nombre:registro.MUSEO, id:registro.ID_MUSEO});
                    
                if(!findObject(registro.AUTOR, filtrosDeAutores))
                    filtrosDeAutores.push({nombre:registro.AUTOR, id:registro.AUTOR});
            });
            
            construirFiltrosDeMuseos(filtrosDeMuseos);
            construirFiltrosDeAutores(filtrosDeAutores);
            
            registrosActivos = [... registros];
            
            poblarTablaDeRegistros(paginar(registros));
            
            if(fichaSeleccionada != null)
                $("table > tbody > tr > td:nth-of-type(1) > input").removeAttr("disabled");
        }
        
        function cambiarLongitudDeTabla(node){
            paginaActiva = 1;
            registrosPorPagina = node.value;
            filtrarRegistros();
        }
        
        function paginar(elementos){
            var paginador = $(".pagination").first();
            paginador.empty();
            
            var contador = Math.floor(elementos.length / registrosPorPagina);
            if((elementos % registrosPorPagina) != 0)
                contador += 1;
            paginador.append('<button onclick="cambiarPaginaAnterior('+contador+')">&laquo;</button>');
            for(var i = 0 ; i < contador ; i++){
                var activa = "";
                if((i+1) == paginaActiva )
                    activa = "active";
                paginador.append('<button class="' + activa + '" onclick="cambiarPagina(' + (i+1) + ')">' + (i+1) + '</button>');
            }
            paginador.append('<button onclick="cambiarPaginaSiguiente('+contador+')">&raquo;</button>');
            
            var min = registrosPorPagina * (paginaActiva - 1);
            var max = registrosPorPagina * paginaActiva;
            return elementos.slice(min, max);
        }
        
        function cambiarPaginaSiguiente(contadorPaginas){
            if(contadorPaginas > 1 && paginaActiva < contadorPaginas){
                paginaActiva ++;
                poblarTablaDeRegistros(paginar(registrosActivos));
                //filtrarRegistros();
            }
        }
        
        function cambiarPaginaAnterior(contadorPaginas){
            if(contadorPaginas > 1 && paginaActiva > 1){
                paginaActiva --;
                poblarTablaDeRegistros(paginar(registrosActivos));
                //filtrarRegistros();
            }
        }
        
        function cambiarPagina(target){
            paginaActiva = target;
            poblarTablaDeRegistros(paginar(registrosActivos));
            //filtrarRegistros();
        }

        function poblarTablaDeRegistros(registros){
            
            console.log("procesando: ", registros);
            
            
            
            

            //Vaciar contenedor de registros
            var contenedorDeRegistros = $("#exportTable > tbody").first();
            contenedorDeRegistros.empty();
            
            

            $.each(registros,function(index, registro){
                var registroHTML = '<tr><td><input type="checkbox" disabled onclick="agregarFichaAReporte(this, \'' + registro.ID + '\')"></td>';
                registroHTML += '<td style="display:none;"><input style="visibility: hidden; width: 1%; height: 1%;" value="' + registro.ID + '" name="folio" readonly="">' + registro.ID + '</td>';
                
                
                registroHTML += (registro.thumb != undefined ? '<td><img style="height: 80px;" src="' + registro.thumb + '"/></td>':'<td></td>');
                
                registroHTML += '<td>' + registro.nombreDeObra + '</td>';
                
                //registroHTML += '<td>' + registro.USUARIO_REGISTRO + '</td>';
                if(registro.AUTOR == ""){
                    registro.AUTOR = "Desconocido";
                }
                registroHTML += '<td>' + registro.AUTOR + '</td>';
                registroHTML += '<td>' + registro.MUSEO + '</td>';
                registroHTML += '<td>' + registro.COLECCION + '</td>';
                registroHTML += '<td>' + registro.inventario + '</td>';
                
                
                registroHTML += '<td><span class="label label-sm label-success">' + registro.ESTATUS + '</span></td>';
                var botonValidar = (registro.ACCIONES.includes('VALIDAR')) ? '<a href="#"><button class="btn btn-success btn-xs" name="btnRevision"><i class="fa fa-check"></i></button></a>': "";
                var botonEliminar = (registro.ACCIONES.includes('ELIMINAR')) ? '<a href="#"><button class="btn btn-papelera btn-xs" name="btnPapelera"><i class="fa fa-trash-o"></i></button></a>': "";
                var botonEditar = (registro.ACCIONES.includes("EDITAR")) ? '<a href="main.php?mr=2&amp;fi=' + registro.ID + '&amp;f=' + registro.ID_FICHA + '&amp;p=' + registro.ID_PERIODO + '"><button class="btn btn-editar btn-xs"><i class="fa fa-pencil"></i></button></a>' : ''; 
                var inputFields = '<input type="hidden" value="' + registro.ID + '" name="folio" readonly=""><input type="hidden" value="' + $("#currentUserId").val() + '" name="usuario" readonly=""><input type="hidden" value="' + registro.ID_USUARIO + '" name="usuarioDestino" readonly=""><input type="hidden" value="' + registro.ID_PERIODO + '" name="coleccion" readonly="">';
                registroHTML += '<td><div class="accnstbl"><a href="main.php?mr=25&amp;idr=' + registro.ID + '"><button class="btn btn-editar btn-xs"><i class="fa fa-eye"></i></button></a>'+ botonEditar + '<form role="form" action="accionRegistro.php" method="post">' + inputFields + botonValidar + botonEliminar + '</form></div></td>';
                registroHTML += '<td>' + registro.USUARIO_REGISTRO + '</td>';
                registroHTML += '</tr>';
                contenedorDeRegistros.append(registroHTML);
            });
        }

        function findObject(name, array){
            var found = false;
            for(var i = 0; i < array.length; i++) {
                if (array[i].nombre == name) {
                    found = true;
                    break;
                }
            }
            return found;
        }
        
        function construirFiltrosDeMuseos(museos){
            museos.forEach(function(museo){
                var string = '<li><input type="checkbox" id="" name="museos[]" value="' + museo.id + '" class="to-fa facetadores" onclick="accionarFiltroDeMuseo(this)"> <label> ' + museo.nombre + '</label></li>';
                $(".filtro.museos > .filtro__contenido").append(string);
            });
            if(museoSeleccionado != null){
                $(".filtro.museos > .filtro__contenido > li > input").prop("checked", true);
            }
        }
        
        
        
        

        
        
        function construirFiltrosDeAutores(autores){
            autores.forEach(function(autor){
                var string = '<li><input type="checkbox" id="" name="autores[]" value="' + autor.id + '" class="to-fa facetadores" onclick="accionarFiltroDeAutor(this)"> <label> ' + autor.nombre + '</label></li>';
                $(".filtro.autores > .filtro__contenido").append(string);
            });
            if(autorSeleccionado != null){
                $(".filtro.autores > .filtro__contenido > li > input").prop("checked", true);
            }
        }
        
        function accionarFiltroDeMuseo(filtro){
            if($(filtro).prop('checked')){
                //fue activado...
                museoSeleccionado = $(filtro).val();
            } else {
                museoSeleccionado = null;
            }
            filtrarRegistros();
        }
        
        
        function accionarFiltroDeAutor(filtro){
            if($(filtro).prop('checked')){
                //fue activado...
                autorSeleccionado = $(filtro).val();
            } else {
                autorSeleccionado = null;
            }
            filtrarRegistros();
        }
        
        
        function agregarFichaAReporte(nodo, idDeFicha){
            if($(nodo).prop("checked")){
                fichasParaReporte.push(idDeFicha);
            } else {
                //Eliminar
                for( var i = 0; i < fichasParaReporte.length; i++){ 
                    if ( fichasParaReporte[i] === idDeFicha) {
                        fichasParaReporte.splice(i, 1); 
                        i--;
                    }
                }
            }
             console.log(fichasParaReporte);
        }
        

        function buscarPorFolio(string){
            var filtrados = [];
            paginaActiva = 1;
            if(string.length > 1){
                $.each(registrosVisibles,function(index, registro){
                    if(registro.ID.toLowerCase().includes(string.toLowerCase()) || registro.AUTOR.toLowerCase().includes(string.toLowerCase()) || registro.inventario.toLowerCase().includes(string.toLowerCase()) || registro.nombreDeObra.toLowerCase().includes(string.toLowerCase()))
                        filtrados.push(registro);
                });
                
                registrosVisibles = filtrados;
                filtrarRegistros();
                registrosVisibles = [...registrosActuales];
                completeList = false;
            } else {
                if(!completeList){
                    filtrarRegistros();
                    completeList = true;
                }
            }
        }
        
    </script>

</div><!--//card-body-->
