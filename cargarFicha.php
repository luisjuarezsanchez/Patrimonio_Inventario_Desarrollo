
<?php 

$microtime =microtime(true);//fecha y hora de microsegundos
$now = DateTime::createFromFormat('U.u',$microtime );//fecha con formato

?>
<!--Inicia outter-wp-->
<div class="outter-wp">
    <!--Inicia registro-->
    <div class="graph">
        <div id="cabecera">
            <h3 class="title">Consulta de registro</h3>

        </div>
        <div id="formulario">
            <form role="form" action="main.php?op=2&p=<?php echo $periodo; ?>&f=<?php echo $fichaSeleccionada; ?>" method="post" enctype="multipart/form-data">

                <div class="fila">
                    <div class="col-md-12"><label><?php echo obtenerNombreFicha($fichaSeleccionada);?></label></div>

                </div>

                <div class="fila">
                    <div class="col-md-6">
                        <div class="col-md-4">
                        <label for="fecha">Fecha </label>
                        </div>
                        <div class="col-md-8">
                        <input type="text" id="fecha"  name="fecha" size="25" class="x" readonly value="<?php echo $now->format("d-m-Y");?>"
                               maxlength="50">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                        <label for="folio">Folio </label>
                        </div>
                        <div class="col-md-8">
                        <input type="text" id="folio" name="folio"  size="25" class="x" readonly value="<?php echo "REG".$fichaSeleccionada."-".$microtime;?>"
                               maxlength="50" >
                        </div>
                        <div class="clearfix"></div>
                    </div>

                </div>
                <?php
                $arraySecciones = obtenerSecciones($fichaSeleccionada);
                foreach ($arraySecciones as $seccion)
                {
                    $dosCampos="";
                    $counterCampos=0;
                    ?>
                    <div id="cabecera2">
                        <h3 class="sub-title"><?php echo  $seccion->nombre;?></h3>
                    </div>

                    <?php
                    $sizeFields = sizeof($seccion->campos );
                    $counterFields=0;
                    $counterRows=0;
                    foreach ($seccion->campos as $campo) {
                         if($campo->obligatorio)
                            $campo->nombre = "<span style='color: red; '>* </span>".$campo->nombre;
                        if($campo->tipo==1)
                        {
                            $nombrePeriodo="";
                            if($campo->id==2)
                                $nombrePeriodo= obtenerNombrePeriodo($periodo);
                            $obligatorio= ($campo->obligatorio)?"required": '';
                            $dosCampos.="<div class='col-md-6'  id='col".$campo->id."'>
                                    <div class='col-md-4'><label for='".$campo->id."'>".$campo->nombre."</label></div>
                                    <div class='col-md-8'><input id='".$campo->id."' name='".$campo->id."' value='".$nombrePeriodo."' type='text' class='x' ".$obligatorio."></div>
                                </div>";
                            $counterCampos++;
                        }else if ($campo->tipo==2)
                        {
                            $obligatorio= ($campo->obligatorio)?"required": '';
                            $dosCampos.="<div class='col-md-12'  id='col".$campo->id."'>
                                    <div class='col-md-2'><label for='".$campo->id."'>".$campo->nombre."</label></div>
                                    <div class='col-md-10'><textarea id='".$campo->id."' name='".$campo->id."'  rows='6' class='x' ".$obligatorio."></textarea></div>
                                </div>";
                            $counterCampos=2;
                        }else if ($campo->tipo==3)
                        {
                            $opcionesString="";
                            if($campo->id != 67 && $campo->id != 68 && $campo->id != 69)
                            foreach ($campo->opciones as $opcion) {
                                $opcionesString .=" <option value='". $opcion->id."'>".$opcion->nombre."</option>";
                            }
                            $obligatorio= ($campo->obligatorio)?"required": '';
                            $bndSeleccion="";
                            if($campo->id==4 || $campo->id== 5)
                                $bndSeleccion="disabled";
                            $dosCampos.="<div class='col-md-6' id='col".$campo->id."'>
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
                        if($counterCampos==2)
                        {
                            if($counterRows%2!=0)
                                $filaColor="gris";
                            ?>
                            <div class="fila <?php echo $filaColor;?>">
                                <?php
                                echo $dosCampos;
                                $counterCampos=0;
                                $dosCampos="";
                                ?>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                            $counterRows++;
                        }

                        $counterFields++;
                        if($counterFields == $sizeFields && $dosCampos!=="")
                        {
                            if($counterRows%2!=0)
                                $filaColor="gris";
                            ?>
                            <div class="fila <?php echo $filaColor;?>">
                                <?php
                                echo $dosCampos;
                                $counterCampos=0;
                                $dosCampos="";
                                ?>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                            $counterFields=0;
                        }

                    }

                    if($seccion->bndFiles==1)
                    {
                        ?>
                        <div class="fila" style="height: 200px;">
                            <div class="col-md-12">
                                <div class="col-md-4"><label>Objeto digital</label><br><label style=" display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 200;
    font-size: 0.8em;
    color: #777;">Selecciona hasta 5 objetos a la vez</label></div>
                                <div class="col-md-8">
                                    <div class="galeria">
                                        <ul id="archivosGuardados">

                                        </ul>
                                    </div>
                                </div>
                            </div><!--Termina columna 1-->
                            <div class="col-md-12">
                                <div class="col-md-4"><label></label></div>
                                <div class="col-md-8">
                                  <label id= "objectsCounterSelected" for="archivos" class="a_demo_four">Cargar aquí (0 Objetos)</label>
                                <input style="display:none;"type="file" class="form-control" id="archivos" name="archivos[]" multiple="multiple" accept="application/pdf, image/jpeg, image/jpg"/>
                                               
                                </div><!--Termina columna 2-->
                            </div>
                            
                            

                        </div><!--Termina fila gris-->
                            <script>
                             var tamArchivosGuardados=0;
                            $("#archivos").on("change", function() {

                                var totalArchivos=$("#archivos")[0].files.length+ tamArchivosGuardados;
                                if (totalArchivos > 5) {
                                    alert("Solo puedes subir un máximo de 5 archivos");
                                    $("#archivos").val('');
                                    document.getElementById('objectsCounterSelected').innerHTML='Cargar aquí (0 Objetos)';
                                }else
                                {
                                    var totalSize=0;
                                    var files = $('#archivos')[0].files;
                                    for (var i = 0; i < files.length; i++) {
                                        // calculate total size of all files
                                        totalSize += files[i].size;
                                    }
                                    
                                    document.getElementById('objectsCounterSelected').innerHTML='Cargar aquí ('+files.length+' Objetos)';

                                    if(totalSize>104857600)
                                    {
                                        alert("El tamaño total es de 10MB");
                                        $("#archivos").val('');
                                        document.getElementById('objectsCounterSelected').innerHTML='Cargar aquí (0 Objetos)';
                                    }
                                }
                            });
                        </script>
                        <?php
                    }
                }
                ?>
                <div class="botones">
                    <button type="submit" name="btnActualizar" id="btnActualizar" class="btn guardar">GUARDAR CAMBIOS</button>
                </div>
            </form>
        </div>
    </div>
</div><!--Termina outter-wp-->


