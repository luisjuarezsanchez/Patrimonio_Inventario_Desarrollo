<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 03/08/2019
 * Time: 05:10 PM
 */
 $notificaciones=  obtenerNotificacionesPendientes($idUsuario);
?>
  <script type="application/javascript">
      var arrayValoresNotificaciones, arrayTmpNotificaciones;
</script>
    
            <div class="inbox-mail">
                <div class="col-md-2 compose">

                    <h2>Filtrar</h2>
                    <nav class="nav-sidebar">
                        <ul class="nav tabs">
                           <li class="active"><a href="#tab1" data-toggle="tab" onclick="obtenerNotifPendientes()"><i class="fa fa-inbox"></i>Reciente <span id="contadorPedientes"><?php echo sizeof($notificaciones); ?></span><div class="clearfix"></div></a></li>
                            <li class=""><a href="#tab2" data-toggle="tab" onclick="obtenerHistorial()"><i class="fa fa-clock-o"></i> Histórico</a></li>
                            <li class=""><a href="#tab2" data-toggle="tab" onclick="obtenerAprobados()"><i class="fa fa-check"></i> Aprobado</a></li>
                        </ul>
                    </nav>

                </div>
                <!-- tab content -->
                <div class="col-md-10 tab-content tab-content-in">
                    <div class="tab-pane active text-style" id="tab1">
                        <div class="inbox-right">

                            <div class="mailbox-content">

                                <div class="mail-toolbar clearfix">
                                    <div class="float-left">



                                    </div>
                                    <div class="float-right">
                                        <div class="btn-group m-r-sm mail-hidden-options" style="display: inline-block;">
                                            <div class="btn-group">
                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-folder"></i> <span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                     <li><a href="#" onclick="filtrarPorColeccion(1)">Prehistórico</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(2)">Arqueológico</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(3)">Virreinato</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(4)">Arte Moderno</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(5)">Culturas Populares</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(6)">Numismática</a></li>
                                                     <li><a href="#" onclick="filtrarPorColeccion(7)">Historia Natural</a></li>
                                                      <li><a href="#" onclick="filtrarPorColeccion(8)">Objeto Histórico</a></li>
                                                </ul>
                                            </div>
                                            <div class="btn-group">
                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tags"></i> <span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                     <li><a href="#" onclick="filtrarPorTipo(1)">Mensajes</a></li>
                                                    <li><a href="#" onclick="filtrarPorTipo(2)">Notificaciones</a></li>

                                                </ul>
                                            </div>
                                        </div>



                                    </div>

                                </div>


                                <table class="table">
                                    <thead style="background:#666;">
                                    <th></th>
                                    <th style="width:55%;">Mensaje</th>
                                    <th>Colección</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    </thead>
                                    <tbody id="tab1Body">
                                    

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane text-style" id="tab2">
                        <div class="inbox-right">

                            <div class="mailbox-content">

                                <div class="mail-toolbar clearfix">
                                    <div class="float-left">



                                    </div>
                                    <div class="float-right">
                                        <div class="btn-group m-r-sm mail-hidden-options" style="display: inline-block;">
                                            <div class="btn-group">
                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-folder"></i> <span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a href="#" onclick="filtrarPorColeccion(1)">Prehistórico</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(2)">Arqueológico</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(3)">Virreinato</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(4)">Arte Moderno</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(5)">Culturas Populares</a></li>
                                                    <li><a href="#" onclick="filtrarPorColeccion(6)">Numismática</a></li>
                                                     <li><a href="#" onclick="filtrarPorColeccion(7)">Historia Natural</a></li>
                                                      <li><a href="#" onclick="filtrarPorColeccion(8)">Objeto Histórico</a></li>
                                                </ul>
                                            </div>
                                            <div class="btn-group">
                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tags"></i> <span class="caret"></span></a>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a href="#" onclick="filtrarPorTipo(1)">Mensajes</a></li>
                                                    <li><a href="#" onclick="filtrarPorTipo(2)">Notificaciones</a></li>

                                                </ul>
                                            </div>
                                        </div>



                                    </div>

                                </div>


                                <table class="table">
                                    <thead style="background:#666;">
                                    <th></th>
                                    <th style="width:55%;">Mensaje</th>
                                    <th>Colección</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    </thead>
                                    <tbody id="tab2Body">

                                  


                                   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="clearfix"> </div>
            </div>
            
            <script type="application/javascript">

    var idUsuarioNotificaiones=<?php echo $idUsuario;?>;
    var bndMensajes= 0;
    var bndNotificaciones = 0;
    
    var bndPendientes = 1;
    var bndHistorial = 0;
    
    function tratarNotificaciones(jsonNotificaciones,contenedor) {
       
        var contenedorNotificacionesPendientes = document.getElementById(contenedor);
        contenedorNotificacionesPendientes.innerHTML="";
        jsonNotificaciones.forEach(function (valor) {
            var tipoIcono= (valor.tipo==1)?"envelope-o":"bell";
            var colorColeccion="";
            if(valor.idColeccion == 1)
                colorColeccion="fam";
                else if(valor.idColeccion == 2)
                colorColeccion="notarq";
            else if(valor.idColeccion == 3)
                colorColeccion="work";
                else if(valor.idColeccion == 4)
                colorColeccion="notart";
                else if(valor.idColeccion == 5)
                colorColeccion="notcul";
                else if(valor.idColeccion == 6)
                colorColeccion="notnum";
                else if(valor.idColeccion == 7)
                colorColeccion="nothis";
                else if(valor.idColeccion == 8)
                colorColeccion="notobj";
            contenedorNotificacionesPendientes.innerHTML +=  "<tr class='table-row'> <td class='table-img'> <img src='"+valor.imagen+"' alt='' style='width:60px; heght:60px;' /> </td> <td class='table-text'> <h6> "+valor.titulo+"</h6> <p>"+valor.mensaje+"</p> </td> <td> <span class='"+colorColeccion+"'>"+valor.coleccion+"</span> </td> <td> <span>"+valor.estadoRegistro+"</span> </td> <td class='march'>"+valor.fecha+" </td> <td > <i class='fa fa-"+tipoIcono+" icon-state-warning'></i> </td> </tr>";
        });
    }
    
    function filtrarNotificaciones(idColeccion,tipoNot,contenedor)
    {

        var contenedorNotificacionesPendientes = document.getElementById(contenedor);
        contenedorNotificacionesPendientes.innerHTML="";
        arrayTmpNotificaciones.forEach(function (valor) {
            
            var tipoIcono= (valor.tipo==1)?"envelope-o":"bell";
                var colorColeccion="";
                if(valor.idColeccion == 1)
                    colorColeccion="fam";
                    else if(valor.idColeccion == 2)
                    colorColeccion="notarq";
                else if(valor.idColeccion == 3)
                    colorColeccion="work";
                    else if(valor.idColeccion == 4)
                    colorColeccion="notart";
                    else if(valor.idColeccion == 5)
                    colorColeccion="notcul";
                    else if(valor.idColeccion == 6)
                    colorColeccion="notnum";
                    else if(valor.idColeccion == 7)
                    colorColeccion="nothis";
                    else if(valor.idColeccion == 8)
                    colorColeccion="notobj";
                    
            if(tipoNot!= 0 && idColeccion != 0 && idColeccion == valor.idColeccion)
            {
                bndMensajes=1;
                if(tipoNot == valor.tipo)
                contenedorNotificacionesPendientes.innerHTML +=  "<tr class='table-row'> <td class='table-img'> <img src='"+valor.imagen+"' alt='' style='width:60px; heght:60px;' /> </td> <td class='table-text'> <h6> "+valor.titulo+"</h6> <p>"+valor.mensaje+"</p> </td> <td> <span class='"+colorColeccion+"'>"+valor.coleccion+"</span> </td> <td> <span>"+valor.estadoRegistro+"</span> </td> <td class='march'>"+valor.fecha+" </td> <td > <i class='fa fa-"+tipoIcono+" icon-state-warning'></i> </td> </tr>";

            }
            else if(idColeccion == valor.idColeccion)
            {
                bndNotificaciones=1;
                contenedorNotificacionesPendientes.innerHTML +=  "<tr class='table-row'> <td class='table-img'> <img src='"+valor.imagen+"' alt='' style='width:60px; heght:60px;' /> </td> <td class='table-text'> <h6> "+valor.titulo+"</h6> <p>"+valor.mensaje+"</p> </td> <td> <span class='"+colorColeccion+"'>"+valor.coleccion+"</span> </td> <td> <span>"+valor.estadoRegistro+"</span> </td> <td class='march'>"+valor.fecha+" </td> <td > <i class='fa fa-"+tipoIcono+" icon-state-warning'></i> </td> </tr>";
            }

        });
    }
    
    
    function obtenerHistorial()
    {
        $.get("lib/funcionesGenerales.php", {
            estadoNotificacion: 2,
            idusuario: idUsuarioNotificaiones
        },  function (data) {
            arrayValoresNotificaciones = JSON.parse(data);

            tratarNotificaciones(arrayValoresNotificaciones,"tab2Body");
            bndHistorial=1;
            bndPendientes=0;
             bndMensajes=0;
             bndNotificaciones =0;

        });
    }
    
    function obtenerAprobados()
    {
        $.get("lib/funcionesGenerales.php", {
            estadoNotificacion: 3,
            idusuario: idUsuarioNotificaiones
        },  function (data) {
            arrayValoresNotificaciones = JSON.parse(data);

            tratarNotificaciones(arrayValoresNotificaciones,"tab2Body");
            bndHistorial=1;
            bndPendientes=0;
             bndMensajes=0;
             bndNotificaciones =0;

        });
    }

    function obtenerNotifPendientes() {
        $.get("lib/funcionesGenerales.php", {
            estadoNotificacion: 1,
            idusuario: idUsuarioNotificaiones
        }, function (data) {

            arrayValoresNotificaciones = JSON.parse(data);
            document.getElementById("contadorPedientes").innerHTML="0";
            document.getElementById("numGeneralNotifications").innerHTML="0";
            tratarNotificaciones(arrayValoresNotificaciones,"tab1Body");
             bndHistorial=0;
            bndPendientes=1;
        });
    }

var colectionSelected=0;
    function filtrarPorColeccion(idColeccion)
    {
        colectionSelected=idColeccion;
        if(bndMensajes==1 && bndNotificaciones==1)
        {
            bndMensajes=0;
            bndNotificaciones=0;
        } 
        
        if(bndMensajes==0 && bndNotificaciones==0)
        arrayTmpNotificaciones=arrayValoresNotificaciones;
        
        
        if(bndHistorial== 1)
        filtrarNotificaciones(idColeccion,0,"tab2Body");
        else
                filtrarNotificaciones(idColeccion,0,"tab1Body");

    }
    
    function filtrarPorTipo(tipo)
    {
        if(colectionSelected!=0){
            
        
            if(bndMensajes==1 && bndNotificaciones==1)
            {
                bndMensajes=0;
                bndNotificaciones=0;
            }
            
            if(bndMensajes==0 && bndNotificaciones==0)
            arrayTmpNotificaciones=arrayValoresNotificaciones;
            
            
            if(bndHistorial== 1)
                filtrarNotificaciones(colectionSelected,tipo,"tab2Body");
            else
                filtrarNotificaciones(colectionSelected,tipo,"tab1Body");

        }else{
            alert("Selecciona una colección");
        }
        
        
        
        
        
    }
    
    $( document ).ready(function() {
     obtenerNotifPendientes();
});
</script>
