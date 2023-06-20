<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 15/06/2019
 * Time: 07:23 AM
 */

//include "cargarFicha.php";
include "plantillaEditarFicha.php"

?>



<script type="text/javascript">
    time = parseFloat("<?php echo $_GET["fi"]; ?>".split("-")[1]) * 1000;
    var date = new Date(time);
    var fechaDeFicha = date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
    $("#folio").val("<?php echo $_GET["fi"]; ?>");
    $("#fecha").val(fechaDeFicha);
    var arrayRelaciones = <?php echo json_encode(obtenerRelacionesCampos($fichaSeleccionada)); ?>;

   
      $.ajax({
        type: "GET",
        cache: false,
        url: "<?php echo $path. $_GET["fi"] . ".json"; ?>",
        //other settings
        success: function(data) {   
        
        $.each(data, function( index, value ) {
            var arrayProcedencia= new Array();

            
            value.campos.forEach(function(campo) {
               $('[name="' + campo.idCampo + '"]').val(campo.valor);

               arrayRelaciones.forEach(function (relacion) {
                   if(relacion.id_campo==campo.idCampo && campo.valor==0)
                           document.getElementById("col"+campo.idCampo).style.display = "none";
               });

                 if (campo.idCampo==66 || campo.idCampo == 67 || campo.idCampo == 68 || campo.idCampo == 69){
                    var campoProcedencia = {
                        "campoOrigen":campo.idCampo,
                        "valorSeleccionado": campo.valor
                    };
                    arrayProcedencia.push(campoProcedencia);
                }
            });
           
            
              if(value.archivos !== "")
                {
                    tamArchivosGuardados=value.archivos.length;

                    value.archivos.forEach(function (t) {
                        var array=t.split("/");
                        var documentType=t.split(".");
                        var pathImage="";
                        if(documentType[documentType.length-1]==="pdf")
                            pathImage="images/thumbs_PDF.jpg";
                        else
                            pathImage=t;

                        document.getElementById("archivosGuardados").innerHTML+="<li class='imagen'>" +
                            "<a href='"+t+"' target='_blank'>" +
                            "<img src='"+pathImage+"' style='width: 60px; height: 60px;'/>" +
                            "<input type='checkbox' name='archivosGuardados[]' value='"+array[array.length-1]+"' class='to-fa facetadores' checked> " +
                            "</a>" +
                            "</li>";
                     
                    });
                }

            
            
            if(arrayProcedencia.length>0)
            {
                $.get("lib/obtenerOpcionesdeRespuestas.php", {
                    jsonProcedencia: JSON.stringify(arrayProcedencia)
                }, function (data) {

                     JSON.parse(data).forEach(function (dato) {
                         var campoValores = dato;//JSON.parse(dato);


                         var campoDestino = document.getElementById(campoValores.campoDestino);
                         campoDestino.innerHTML = "<option value='' selected >Selecciona una opci&oacuten</option>";
                         campoValores.valores.forEach(function (valor) {

                             campoDestino.innerHTML += "<option value='" + valor.id_valor + "' >" + valor.nombre + "</option>"
                         });

                         arrayProcedencia.forEach(function (t) {
                             document.getElementById(t.campoOrigen).value=t.valorSeleccionado;
                         });
                     });
                });
            }

           
            
            });
        }
    });
    

</script>
