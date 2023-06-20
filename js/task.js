var registrosJSON = JSON.parse('<?php echo json_encode($registros, JSON_UNESCAPED_UNICODE); ?>');

var registrosActivos = [... registrosJSON];

var registrosVisibles  = [...registrosJSON];
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

/*
for(var i = 0 ; i < contador ; i++){
    var activa = "";
    if((i+1) == paginaActiva )
        activa = "active";
    paginador.append('<button class="' + activa + '" onclick="cambiarPagina(' + (i+1) + ')">' + (i+1) + '</button>');
}
*/

paginador.append('<button>' + paginaActiva + ' de ' + contador + '</button>');
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

function activarFiltroFicha(selected){
$(".facetador-ficha").prop('checked',false);

$(selected).prop('checked',true);
fichaActiva = $(selected).attr('id')
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
if(fichaActiva != null){
    //eliminar todos aquellos cuyo id de ficha sea diferente a este.
    for( var i = 0; i < registros.length; i++){ 
        if ( registros[i].ID_FICHA != fichaActiva) {
            registros.splice(i, 1);
            i--;
        }
    }
    
}
if(museoActivo != null){
    //eliminar todos aquellos cuyo id de ficha sea diferente a este.
    for( var i = 0; i < registros.length; i++){ 
        if ( registros[i].ID_MUSEO != museoActivo) {
            registros.splice(i, 1);
            i--;
        }
    }
}
if(usuarioActivo != null){
    //eliminar todos aquellos cuyo id de ficha sea diferente a este.
    for( var i = 0; i < registros.length; i++){ 
        if ( registros[i].ID_USUARIO != usuarioActivo) {
            registros.splice(i, 1);
            i--;
        }
    }
}





if(autorActivo != null){
    //eliminar todos aquellos cuyo id de ficha sea diferente a este.
    for( var i = 0; i < registros.length; i++){ 
        if ( registros[i].AUTOR != autorActivo) {
            registros.splice(i, 1);
            i--;
        }
    }
}

filtrosDeUsuarios = [];
filtrosDeMuseos = [];
filtrosDeFichas = [];
filtrosDeAutores = [];

$.each(registros, function(index, registro){
    if(!findObject(registro.MUSEO, filtrosDeMuseos))
        filtrosDeMuseos.push({nombre:registro.MUSEO, id:registro.ID_MUSEO});
    if(!findObject(registro.USUARIO_REGISTRO, filtrosDeUsuarios))
        filtrosDeUsuarios.push({nombre: registro.USUARIO_REGISTRO, id: registro.ID_USUARIO});
    if(!findObject(registro.FICHA, filtrosDeFichas))
        filtrosDeFichas.push({nombre: registro.FICHA, id: registro.ID_FICHA});
        
    if(!findObject(registro.AUTOR, filtrosDeAutores))
        filtrosDeAutores.push({nombre: registro.AUTOR, id: registro.AUTOR});
});

$(".filtro.usuarios > .filtro__contenido").empty();
$(".filtro.museos > .filtro__contenido").empty();
$(".filtro.autores > .filtro__contenido").empty();

construirFiltrosDeMuseos(filtrosDeMuseos);
construirFiltrosDeUsuarios(filtrosDeUsuarios);
configurarFiltrosDeAutores(filtrosDeAutores);

registrosActivos = [... registros];

poblarTablaDeRegistros(paginar(registros));
if(fichaActiva != null)
    $("table > tbody > tr > td:nth-of-type(1) > input").removeAttr("disabled");

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

function poblarTablaDeRegistros(registros){
//registrosVisibles = registros;

console.log("procesando: ", registros);

//Vaciar contenedor de registros
var contenedorDeRegistros = $("#exportTable > tbody").first();
contenedorDeRegistros.empty();

$.each(registros,function(index, registro){
    console.log("processing => ", registro);
    //var enReporte = (fichasParaReporte.includes(registro.ID)) ? ""
    var registroHTML = '<tr><td><input type="checkbox" disabled onclick="agregarFichaAReporte(this, \'' + registro.ID + '\')"></td>';
    registroHTML += '<td style="display:none;"><input style="visibility: hidden; width: 1%; height: 1%;" value="' + registro.ID + '" name="folio" readonly="">' + registro.ID + '</td>';
    
    registroHTML += (registro.thumb != undefined ? '<td><img style="height: 80px;" src="' + registro.thumb + '"/></td>':'<td></td>');
    
    registroHTML += '<td>' + registro.nombreDeObra + '</td>';
    
    if(registro.AUTOR == ""){
        registro.AUTOR = "Desconocido";
    }
    registroHTML += '<td>' + registro.AUTOR + '</td>';
    registroHTML += '<td>' + registro.MUSEO + '</td>';
    registroHTML += '<td>' + registro.COLECCION + '</td>';
    registroHTML += '<td>' + registro.inventario + '</td>';
    
    registroHTML += '<td><span class="label label-sm label-success">' + ((registro.ID_ESTATUS_REGISTRO == 2) ? "Revisión" : "Validado") + '</span></td>';
    var botonValidar = (registro.ACCIONES.includes('VALIDAR')) ? '<a href="#"><button class="btn btn-success btn-xs" name="btnValidar"><i class="fa fa-check"></i></button></a>': "";
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

function configurarFiltrosDeAutores(autores){
autores.forEach(function(autor){
    var string = '<li><input type="checkbox" id="" name="autores[]" value="' + autor.id + '" class="to-fa facetadores" onclick="accionarFiltroDeAutor(this)"> <label> ' + autor.nombre + '</label></li>';
    $(".filtro.autores > .filtro__contenido").append(string);
});
if(autorActivo != null){
    $(".filtro.autores > .filtro__contenido > li > input").prop("checked", true);
}
}

function construirFiltrosDeMuseos(museos){
museos.forEach(function(museo){
    var string = '<li><input type="checkbox" id="" name="museos[]" value="' + museo.id + '" class="to-fa facetadores" onclick="accionarFiltroDeMuseo(this)"> <label> ' + museo.nombre + '</label></li>';
    $(".filtro.museos > .filtro__contenido").append(string);
});
if(museoActivo != null){
    $(".filtro.museos > .filtro__contenido > li > input").prop("checked", true);
}
}

function accionarFiltroDeUsuario(filtro){
if($(filtro).prop('checked')){
    //fue activado...
    usuarioActivo = $(filtro).val();
} else {
    usuarioActivo = null;
}
filtrarRegistros();
}

function accionarFiltroDeMuseo(filtro){
if($(filtro).prop('checked')){
    //fue activado...
    museoActivo = $(filtro).val();
} else {
    museoActivo = null;
}
filtrarRegistros();
}

function accionarFiltroDeAutor(filtro){
if($(filtro).prop('checked')){
    //fue activado...
    autorActivo = $(filtro).val();
} else {
    autorActivo = null;
}
filtrarRegistros();
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
    registrosVisibles = [...registrosJSON];
    completeList = false;
} else {
    if(!completeList){
        filtrarRegistros();
        completeList = true;
    }
}
}