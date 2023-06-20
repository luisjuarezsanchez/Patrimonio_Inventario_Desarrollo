<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 01/07/2019
 * Time: 02:02 PM
 */
$roles = obtenerRoles();
$campoDependencia = obtenerCampo(1);
?>
<div class="fila">
    <div class="col-md-12">
        <h1 style="color: grey;">Agregar nuevo usuario</h1>
    </div>
    <div class="clearfix"></div>

</div>

<!--Inicia outter-wp-->
<div class="outter-wp">
    <!--Inicia registro-->
    <div class="graph">
        <div id="cabecera">
            <h3 class="title">Ficha de registro de un nuevo usuario</h3>
        </div>
        <div id="formulario">
            <form action="registrarUsuario.php" method="post" role="form" enctype="multipart/form-data">
                <div class="fila">
                    <div class="col-md-6">
                        <div class="col-md-4"><label>Nombre</label></div>
                        <div class="col-md-8"><input name="nombre" type="text" class="x" required></div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4"><label>Apellidos</label></div>
                        <div class="col-md-8"><input name="apellidos" type="text" class="x" required></div>
                    </div>
                    <div class="clearfix"></div>

                </div><!--Termina fila-->
                <div class="fila">
                    <div class="col-md-6">
                        <div class="col-md-4"><label>Correo Electrónico</label></div>
                        <div class="col-md-8"><input name="email" type="email" class="x" required></div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4"><label>Rol</label></div>
                        <div class="col-md-8">
                            <div class="caja">
                                <select name="rol" size="1" class="selector" required>
                                    <?php
                                            foreach ($roles as $rol)
                                            {
                                                ?>
                                                <option value="<?php echo $rol->id;?>"><?php echo $rol->rol;?></option>
                                                <?php
                                            }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                   <div class="clearfix"></div>
                </div><!--Termina fila-->
                <div class="fila">
                    <div class="col-md-6">
                        <div class="col-md-4"><label>Dependencia</label></div>
                        <div class="col-md-8">
                            <div class="caja">
                                <select name="dependencia" size="1" class="selector" required>
                                    <?php
                                    foreach ($campoDependencia->opciones as $dependencia)
                                    {
                                        ?>
                                        <option value="<?php echo $dependencia->id;?>"><?php echo $dependencia->nombre;?></option>
                                        <?php
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4"><label>Foto de perfil</label></div>
                        <div class="col-md-8">

                            <input type="file" name="profilePhoto" id="profilePhoto" accept="image/jpeg">
                        </div>
                    </div><!--Termina columna 1-->
                    <div class="clearfix"></div>
                </div><!--Termina fila-->


                <button class="btn btn-success" type="submit" style="float: right; display: block; bottom: 1.5em; right: 2.0em;">Registrar</button>
                                    <div class="clearfix"></div>

            </form>
        </div>
    </div>
</div>
