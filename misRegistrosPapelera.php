<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 10/06/2019
 * Time: 06:36 PM
 */
$registrosObtenidos = obtenerRegistrosPapelera($idUsuario);
?>

<div class="facetador">
    <div class="title_facetador">
        <div id="hamburger-button-container"></div>
        <h4>Filtrar</h4>
    </div>
    <div class="row_main mb40">
        <div class="col-md-6">
            <div class="filtro">
                <div class="titulo_filtro">
                    Colección
                </div>
<ul class="filtro__contenido">
	<li>
		<label>Prehistórico</label>
		<ul class="sub-ficha">
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="1" onclick="activarFiltroFicha(this)"/>
				<label>Prehistórico</label>
			</li>
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="2" onclick="activarFiltroFicha(this)"/>
				<label>Arte Rupestre y Petroglifos</label>
			</li>
		</ul>
	</li>
	<li>
		<label>Arqueológico</label>
		<ul class="sub-ficha">
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="3" onclick="activarFiltroFicha(this)"/>
				<label>Vasijas</label>
			</li>
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="4" onclick="activarFiltroFicha(this)"/>
				<label>Esculturas y Figurillas</label>
			</li>
			<li>
				<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="5" onclick="activarFiltroFicha(this)"/>
				<label>Objetos en General</label>
			</li>
		</ul>
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
		<input type="radio" class="to-fa facetadores facetador-ficha" name="ficha-3" id="13" onclick="activarFiltroFicha(this)"/>
		<label>Objeto Histórico</label>
	</li>
</ul>

            </div><!--Termina filtro-->
        </div><!--Termina col-md-4-->
        <div class="col-md-6">
            <div class="filtro usuarios">
                <div class="titulo_filtro">
                    Usuario de registro
                </div>
                <ul class="filtro__contenido">
                </ul><!--Termina ul filtro_contenido-->
            </div><!--Termina filtro-->
        </div>
        <div style="clear:both"></div>
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
        <thead class="principal">
        <tr>
            <th>Folio</th>
            <th>Usuario de registro</th>
            <th>Estatus anterior</th>
            <th>Acciones</th>
            <th>Fecha de registro</th>
            <th>Fecha de borrado</th>
            <th>Colección</th>
            <th>Ficha</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="9">
                <div class="pagination table">
                    <a href="#">&laquo;</a>
                    <a href="#">1</a>
                    <a class="active" href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#">5</a>
                    <a href="#">6</a>
                    <a href="#">&raquo;</a>
                </div>
        </tr>
        </tfoot>
        <tbody>
        <?php
        
        //Hasta este punto, $registrosObtenidos contiene todos los que se encuentran en la papelera :)
        
        
        
        
        foreach ($registrosObtenidos as $registro)
        {
            
        ?>
        <tr>
            <td>
                <input type="checkbox"></input>
            </td>
            <td><?php echo $registro->folio; ?></td>
            <td><?php echo $registro->nombre . " " . $registro->apellidos; ?></td>
            <td>
                <span class="label label-sm label-success"><?php echo $registro->estatus; ?></span>
            </td>
            <td>
                <div class="accnstbl">
                    
                    <?php
                    
                    $registro->{"ACCIONES"} = array("VER");
                    
                    ?>
                    <a href="main.php?mr=2&fi=<?php echo $registro->folio; ?>&f=<?php echo $registro->id_ficha; ?>">
                        <button class="btn btn-editar btn-xs">
                            <i class="fa fa-eye"></i>
                        </button>
                    </a>


                    <form role="form" action="accionRegistro.php" method="post">
                        <input style="visibility: hidden; width: 1%; height: 1%;"
                               value="<?php echo $registro->folio; ?>" name="folio" readonly>
                        <?php
                        if (! empty($global_permisos))
                            array_push($registro->{"ACCIONES"}, "RESTAURAR");
                        {
                                ?>
                                <a href="#">
                                    <button class="btn btn-success btn-xs" name="btnRestaurar">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </a>
                            <?php
                            if (array_search(12, $global_permisos) !== false) {
                                array_push($registro->{"ACCIONES"}, "ELIMINAR");
                                ?>
                                <a href="#">
                                    <button class="btn btn-papelera btn-xs" name="btnBorrado">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </a>
                                <?php
                            }
                        ?>
                            </form>
                            </div>
                            </td>
                            <td><?php echo $registro->fecha; ?></td>
                            <td><?php echo $registro->fecha_borrado; ?></td>
                            <td><?php echo $registro->periodo; ?></td>
                            <td><?php echo $registro->ficha; ?></td>

                            </tr>
                            <?php
                        }
                        
                        
        }
        //echo(json_encode($registrosObtenidos, JSON_UNESCAPED_UNICODE));
        ?>
        <tr ></tr>
    </table>
    
    
    <script>
        
        var registrosJSON = JSON.parse('<?php echo(json_encode($registrosObtenidos, JSON_UNESCAPED_UNICODE)); ?>');
        var registrosVisibles  = [...registrosJSON];
        var completeList = true;
        
        var filtrosDeUsuarios;
        var filtrosDeMuseos;
        
        //Constantes de control
        var fichaActiva = null;
        var museoActivo = null;
        var usuarioActivo = null;
        
        var paginaActiva = 1;
        var registrosPorPagina = 10;
        
        
        
        filtrarRegistros();
        
        function filtrarRegistros(){
            var registros = [...registrosVisibles];
            console.log(registros);
            registros.sort(function (a, b) {
                console.log(a,b);
                var key1 = parseFloat(a.folio.split("-")[1]);
                var key2 = parseFloat(b.folio.split("-")[1]);
                if (key1 > key2)
                    return -1;
                if (key1 < key2)
                    return 1;
                return 0;
            });
            fichasParaReporte = [];
            if(fichaActiva != null){
                //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                for( var i = 0; i < registros.length; i++){ 
                    if ( registros[i].id_ficha != fichaActiva) {
                        registros.splice(i, 1);
                        i--;
                    }
                }
                
            }
            if(usuarioActivo != null){
                //eliminar todos aquellos cuyo id de ficha sea diferente a este.
                for( var i = 0; i < registros.length; i++){ 
                    if ( registros[i].id_usuario != usuarioActivo) {
                        registros.splice(i, 1);
                        i--;
                    }
                }
            }
            
            poblarTablaDeRegistros(paginar(registros));
            if(fichaActiva != null)
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
                filtrarRegistros();
            }
        }
        
        function cambiarPaginaAnterior(contadorPaginas){
            if(contadorPaginas > 1 && paginaActiva > 1){
                paginaActiva --;
                filtrarRegistros();
            }
        }
        function cambiarPagina(target){
            paginaActiva = target;
            filtrarRegistros();
        }
        
        
        
        function poblarTablaDeRegistros(registros){

            console.log("procesando: ", registros);
            
            //Vaciar contenedores de filtros...
            $(".filtro.usuarios > .filtro__contenido").empty();
            $(".filtro.museos > .filtro__contenido").empty();
            
            //Contenedores lógicos de filtros
            filtrosDeUsuarios = [];
            filtrosDeMuseos = [];
            filtrosDeFichas = [];
            
            //Vaciar contenedor de registros
            var contenedorDeRegistros = $("#exportTable > tbody").first();
            contenedorDeRegistros.empty();
            
            
            
            

            $.each(registros,function(index, registro){
                //var enReporte = (fichasParaReporte.includes(registro.ID)) ? ""
                //var registroHTML = '<tr><td><input type="checkbox" disabled onclick="agregarFichaAReporte(this, \'' + registro.folio + '\')"></td>';
                var registroHTML = '<tr>';
                registroHTML += '<td><input style="visibility: hidden; width: 1%; height: 1%;" value="' + registro.folio + '" name="folio" readonly="">' + registro.folio + '</td>';
                registroHTML += '<td>' + registro.nombre + " " + registro.apellidos + '</td>';
                registroHTML += '<td><span class="label label-sm label-success">' + registro.estatus + '</span></td>';
                var botonRestaurar = (registro.ACCIONES.includes('RESTAURAR')) ? '<a href="#"><button class="btn btn-info btn-xs" name="btnPapelera"><i class="fa fa-repeat"></i></button></a>': "";
                var inputFields = '<input type="hidden" value="' + registro.folio + '" name="folio" readonly=""><input type="hidden" value="' + $("#currentUserId").val() + '" name="usuario" readonly=""><input type="hidden" value="' + registro.ID_USUARIO + '" name="usuarioDestino" readonly=""><input type="hidden" value="' + registro.ID_PERIODO + '" name="coleccion" readonly="">';
                registroHTML += '<td><div class="accnstbl"><a href="main.php?mr=25&amp;idr=' + registro.folio + '"><button class="btn btn-editar btn-xs"><i class="fa fa-eye"></i></button></a><form role="form" action="restaurarRegistro.php" method="post">' + inputFields + botonRestaurar + '</form></div></td>';
                registroHTML += '<td>' + registro.fecha + '</td>';
                registroHTML += '<td>' + registro.fecha_borrado + '</td>';
                
                registroHTML += '<td>' + registro.periodo + '</td>';
                registroHTML += '<td>' + registro.ficha + '</td>';
                registroHTML += '</tr>';
                contenedorDeRegistros.append(registroHTML);
                
                var nombreDelUsuario = registro.nombre + " " + registro.apellidos;
                if(!findObject(nombreDelUsuario, filtrosDeUsuarios))
                    filtrosDeUsuarios.push({nombre: nombreDelUsuario, id: registro.id_usuario});
                    
            });
            
            construirFiltrosDeUsuarios(filtrosDeUsuarios);
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
        
        function construirFiltrosDeUsuarios(usuarios){
            usuarios.forEach(function(usuario){
                var string = '<li><input type="checkbox" id="" name="usuarios[]" value="' + usuario.id + '" class="to-fa facetadores" onclick="accionarFiltroDeUsuario(this)"> <label> ' + usuario.nombre + '</label></li>';
                $(".filtro.usuarios > .filtro__contenido").append(string);
            });
            if(usuarioActivo != null){
                $(".filtro.usuarios > .filtro__contenido > li > input").prop("checked", true);
            }
        }
        
        function configurarFiltrosDeFichas(fichas){
            $(".facetador-ficha").attr('disabled', true);
            fichas.forEach(function(ficha){
                console.log("activando(.facetador-ficha[value='" + ficha.id + "']) => " + ficha.nombre);
                $(".facetador-ficha[id='" + ficha.id + "']").attr('disabled', false);
            });
        }
        
        function accionarFiltroDeUsuario(filtro){
            if($(filtro).prop('checked')){
                usuarioActivo = $(filtro).val();
            } else {
                usuarioActivo = null;
            }
            filtrarRegistros();
        }
        
        function activarFiltroFicha(selected){
            $(".facetador-ficha").prop('checked',false);
            $(selected).prop('checked',true);
            fichaActiva = $(selected).attr('id')
            filtrarRegistros();
        }
        
        function buscarPorFolio(string){
            var filtrados = [];
            paginaActiva = 1;
            if(string.length > 1){
                $.each(registrosVisibles,function(index, registro){
                    if(registro.folio.toLowerCase().includes(string.toLowerCase()))
                        filtrados.push(registro);
                });
                
                registrosVisibles = filtrados;
                filtrarRegistros();
                registrosVisibles = [...registrosJSON];
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
