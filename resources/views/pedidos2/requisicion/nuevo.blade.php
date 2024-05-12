<?php
$estatuses = [1=>"En Proceso", 2=>"Surtida"];
?>

@if (!empty($error) )
<div class="ErrorMsg">{{$error}}</div>
@endif


<form action="{{ url('pedidos2/requisicion_crear/'.$order_id.'?paso=1') }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
<div class="Fila"><label>Folio</label><input type="text" name="number" class="form-control" maxlength="24" /></div>

    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        echo "<option value='$k'>$v</option>";
        }
        ?>
    </select>
    </div>

    <div class="Fila"><label>Archivo Factura</label>
    <div>

    <input type="file" name="document" class="form-control" />
    </div>

    </div>

    <div class="Fila"><label>Archivo Requisición</label>
    <div>
    <input type="file" name="requisition" class="form-control" /> 
    </div>

    </div>
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Continuar" /> </div>

</aside>


</form>