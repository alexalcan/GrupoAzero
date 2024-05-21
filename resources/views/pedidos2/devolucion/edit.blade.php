<?php
//$estatuses = [3=>"En Fabricación", 4=>"Fabricado"];
?>
<form action="{{ url('pedidos2/devolucion_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
    <div class="Fila doscol"><label>Fecha</label> <span>{{$ob->created_at}}</span> </div>

    <div class="Fila"><label>Razón</label>
    <select class="form-control" name="reason_id">
        <?php
        foreach ($reasons as $reason) {
        $selected = ($reason->id == $ob->reason_id) ? "selected" : "";
        echo "<option value='$reason->id' $selected >".$reason->reason."</option>";
        }
        ?>
    </select>
    </div>


    <section mode="edit" class='attachList' rel='debed_{{ $ob->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=evidence&event=entregar&debolution_id='.$ob->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=debed_'. $ob->id .'&mode=edit&catalog=evidence&debolution_id='.$ob->id) }}"></section> 

    
    <div class="Fila "><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>