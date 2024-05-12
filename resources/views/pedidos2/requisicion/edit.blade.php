<?php
$estatuses = [1=>"En Proceso", 2=>"Surtida"];
?>
<form action="{{ url('pedidos2/requisicion_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
    <div class="Fila doscol"><label>Número</label> <span>{{$ob->number}}</span> </div>

    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        $selected = ($k == $ob->status_id) ? "selected" : "";
        echo "<option value='$k' $selected >$v</option>";
        }
        ?>
    </select>
    </div>

    <div class="Fila">
        <label>Archivo Factura</label>
        <div>
        @if (isset($ob->document))
        {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
        
        @else

        <div><input type="file" name="document" class="form-control" /> </div>
        @endif
        
        </div>
    </div>


    <div class="Fila">
        <label>Archivo Requisición</label>
        <div>
            <div>
            @if (isset($ob->requisition))
            {{ view('pedidos2/view_storage_item',['path'=>$ob->requisition]) }}
            @else 
            <input type="file" name="requisition" class="form-control" /> 
            @endif
            </div>        
        </div>

    </div>

    
    <div class="Fila "><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>