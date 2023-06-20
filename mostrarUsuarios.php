<?php

$usuariosObtenidos = obtenerUsuarios();
?>

<div class="card-body " id="bar-parent">
    <input type="hidden" value="<?php echo $email; ?>" name="email" readonly id="hidden-email"/>
    <table id="exportTable" class="table table-bordered" style="width:100%">
        <thead class="principal">
        <tr>
            <th>ID</th>
            <th>ESTATUS</th>
            <th>ACCIONES</th>
            <th>NOMBRE</th>
            <th>APELLIDOS</th>
            <th>EMAIL</th>
            <th>ROL</th>
            <th>DEPENDENCIA</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="8">
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
        
        foreach ($usuariosObtenidos as $usuario)
    
        {
        ?>
        <tr>
            <form role="form" action="validarUsuario.php" method="post">
            <td><?php echo $usuario->id; ?></td>
            <td>
                <span class="label label-sm label-success"><?php echo $usuario->estatus; ?></span>
            </td>
            <td>
                <div class="accnstbl">
                    <?php
                    $usuario->{"ACCIONES"} = array();
                    if ($usuario->estatusNum == 1) {
                        if (! empty($global_permisos)) {
                            if (array_search(10, $global_permisos) !== false) {
                                array_push($usuario->{"ACCIONES"}, "AUTORIZAR");
                                ?>

                                <a href="#">
                                    <button class="btn btn-success btn-xs" type="submit">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </a>
                                <?php
                            }
                        }
                        array_push($usuario->{"ACCIONES"}, "RECHAZAR");
                                ?>

                                <a href="#">
                                    <button class="btn btn-rechazar btn-xs">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </a>
                                <?php
                    } else {
                        array_push($usuario->{"ACCIONES"}, "DESHABILITAR");
                        array_push($usuario->{"ACCIONES"}, "EXAMINAR");
                        ?>
                        <a href="#">
                            <button class="btn btn-papelera btn-xs">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </a>
                        
                        <input style="visibility: hidden; width: 1%; height: 1%;" value="<?php echo $usuario->email;?>" name="email" readonly>
                        
                    
                        <a href="main.php?mr=31&target=<?php echo $usuario->id; ?>">
                            
                            <button class="btn btn-info btn-xs">
                                <i class="fa fa-eye"></i>

                            </button>
                        </a>
                        <?php
                    }
                    ?>
                    </div>
                </td>
                <td><?php echo $usuario->nombre;?></td>
                <td><?php echo $usuario->apellidos;?></td>
                <td><?php echo $usuario->email;?></td>
                <td><?php echo $usuario->rol;?></td>
                <td><?php echo $usuario->dependencia;?></td>

            </form>
            </tr>
            <?php
        }
        
        ?>
        
        </tbody>
    </table>
    
    
    <script>
        
        var listaDeUsuarios = JSON.parse('<?php echo json_encode($usuariosObtenidos, JSON_UNESCAPED_UNICODE ); ?>');
        
        var paginaActiva = 1;
        var registrosPorPagina = 10;
        
        var completeList = true;
        

        poblarTablaDeUsuarios(paginarUsuarios(null));
        
        function poblarTablaDeUsuarios(registros){
            var contenedorDeRegistros = $("#exportTable > tbody").first();
            contenedorDeRegistros.empty();
            
            
            
            
            $.each(registros,function(index, registro){
                var registroHTML = '<tr>';
                registroHTML += '<td>' + registro.id + '</td>';
                registroHTML += '<td><span class="label label-sm label-success">' + registro.estatus + '</span></td>';
                
                var botonVer = (registro.ACCIONES.includes('EXAMINAR')) ? '<a href="main.php?mr=31&target=' + registro.id + '"><button class="btn btn-info btn-xs"><i class="fa fa-eye"></i></button></a>': "";
                var botonRechazar = (registro.ACCIONES.includes('RECHAZAR')) ? '<form role="form" action="eliminarUsuario.php" method="post"><a href="#"><button class="btn btn-rechazar btn-xs"><i class="fa fa-times"></i></button></a><input type="hidden" value="' + registro.email + '" name="email" readonly/></form>': "";
                var botonAutorizar = (registro.ACCIONES.includes('AUTORIZAR')) ? '<form role="form" action="validarUsuario.php" method="post"><a href="#"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a><input type="hidden" value="' + registro.email + '" name="email" readonly/></form>': "";

                registroHTML += '<td><div class="accnstbl">' + botonVer + botonAutorizar + botonRechazar +'</div></td>';
                registroHTML += '<td>' + registro.nombre + '</td>';
                registroHTML += '<td>' + registro.apellidos + '</td>';
                registroHTML += '<td>' + registro.email + '</td>';
                registroHTML += '<td>' + registro.rol + '</td>';
                registroHTML += '<td>' + registro.dependencia + '</td>';
                

                registroHTML += '</tr>';
                contenedorDeRegistros.append(registroHTML);
            });
        }
        
        
        
        function paginarUsuarios(usuarios){
            if(usuarios == null)
                usuarios = listaDeUsuarios;
                
                usuarios.sort(function (a, b) {
                    var nombre1 = a.nombre.toLowerCase();
                    var nombre2 = b.nombre.toLowerCase();
                    console.log(nombre1,nombre2);
                    if (nombre1 < nombre2)
                        return -1;
                    if (nombre1 > nombre2)
                        return 1;
                    return 0;
                });
            
            
            var paginador = $(".pagination").first();
            paginador.empty();
            console.log("Existen " + usuarios.length + " usuarios");
            var contador = Math.floor(usuarios.length / registrosPorPagina);
            console.log("hay => ", contador);
            if((usuarios.length % registrosPorPagina) != 0)
                contador += 1;
            console.log("hay => ", contador);
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
            return usuarios.slice(min, max);
        }
        
        function cambiarPaginaSiguiente(contadorPaginas){
            if(contadorPaginas > 1 && paginaActiva < contadorPaginas){
                paginaActiva ++;
                poblarTablaDeUsuarios(paginarUsuarios(null));
            }
        }
        
        function cambiarPaginaAnterior(contadorPaginas){
            if(contadorPaginas > 1 && paginaActiva > 1){
                paginaActiva --;
                poblarTablaDeUsuarios(paginarUsuarios(null));
            }
        }
        function cambiarPagina(target){
            paginaActiva = target;
            poblarTablaDeUsuarios(paginarUsuarios(null));
        }
        
        function cambiarLongitudDeTabla(node){
            paginaActiva = 1;
            registrosPorPagina = node.value;
            console.log(registrosPorPagina);
            poblarTablaDeUsuarios(paginarUsuarios(null));
        }
        
        function buscarPorNombre(string){
            var filtrados = [];
            if(string.length > 1){
                $.each(listaDeUsuarios,function(index, registro){
                    if(registro.nombre.toLowerCase().startsWith(string.toLowerCase()))
                        filtrados.push(registro);
                });
                poblarTablaDeUsuarios(paginarUsuarios(filtrados));
                completeList = false;
            } else {
                if(!completeList){
                    poblarTablaDeUsuarios(paginarUsuarios(null));
                    completeList = true;
                }
            }
        }
    </script>
    
    
    
</div><!--//card-body-->
