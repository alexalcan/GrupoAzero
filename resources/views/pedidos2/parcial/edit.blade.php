<?php
$estatuses = ["5" => "En Puerta", "6"=>"Entregado", "7"=>"Cancelado"];
?>

<form action="{{ url('pedidos2/parcial_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
<div class="Fila doscol"><label>Folio</label> <b>{{ $partial->invoice}} </b></div>

    <div class="Fila doscol"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        $sel = ($k == $partial->status_id) ? "selected" : "";
        echo "<option value='$k' $sel >$v</option>";
        }
        ?>
    </select>
    </div>


    <div class="Fila"><label>Agregar Imágenes</label></div>

    <div id='atlSlot'  val="{{$partial->id}}"
        uploadto="{{ url('pedidos2/attachpost') }}" 
        listHref="{{ url('pedidos2/attachlist') }}">
    </div>  
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>