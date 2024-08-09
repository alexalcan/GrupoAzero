<?php
$estatuses = ["4"=>"Generado", "5" => "En Puerta", "6"=>"Entregado", "7"=>"Cancelado"];
//var_dump($partial);

if($user->department_id==6){
    $estatuses = [6=>"Entregado"];
}


if($user->department_id == 8){
    $estatuses=[5 => "En Puerta"];
}

?>

<form action="{{ url('pedidos2/parcial_update/'.$id) }}" id="FSetParcial" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<input type="hidden" name="uploadto" value="{{ url('pedidos2/attachpost') }}" />
<input type="hidden" name="listHref" value="{{ url('pedidos2/attachlist') }}" />
<input type="hidden" name="partial_id" value="{{ $id }}" />
<input type="hidden" name="urlSetStatus" value="{{ url('pedidos2/set_parcial_status/'.$id) }}" />


<aside class="AccionForm EditInternalScroll">
    
<div class="Fila doscol"><label>Folio</label> <b>{{ $partial->invoice}} </b></div>

    <div class="Fila doscol"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        $sel = ($k == $partial->status_id) ? "selected" : "";
        $propName= "status_".$k;
        echo "<option value='$k' $sel set='". $partial->{$propName} ."' >$v</option>";
        }
        ?>
    </select>
    </div>

    <div class="Fila" id="filaParcialContinuar">
        <input type="button" class="form-control" id="continuarEditParcial" value="Continuar" />
    </div>

    <div id="monitorEditParcial"></div>

    <div class="Fila divAgregarImagenes" style="display: none;"><label>Agregar Imágenes</label></div>
    <div id="alContenedor"></div>


    
    <div class="Fila"  id="terminarEditParcial"  style="display: none;"><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>