<?php
use App\Libraries\Tools;
//var_dump($ob);
?>
<form action="{{ url('pedidos2/shipment_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
    <div class="Fila doscol"><label>Fecha</label> <span>{{ Tools::fechaMedioLargo($ob->created_at) }}</span> </div>

    <div class="Fila doscol"><label>Tipo</label>
    <select class="form-control" name="type">
        <?php
        
        foreach ($types as $k => $v) {
        $sel = ($ob->type == $k) ?"selected" : "";
        echo "<option $sel value='$k'>$v</option>";
        }
        ?>
    </select>
    </div>


    <div class="Fila"><label>Agregar Imágenes</label></div>

    <section mode="edit" class='attachList' rel='shiped' 
        uploadto="{{ url('pedidos2/attachpost?catalog=shipments&shipment_id='.$ob->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=shiped&mode=edit&catalog=shipments&shipment_id='.$ob->id) }}"></section>  
    
    <div class="Fila "><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>