<?php
$estatuses = [1=>"Elaborada", 3 => "En Fabricación", 4=>"Fabricado"];
if($user->role_id == 1 ){
    $estatuses[7] = "Cancelado";
}


?>

@if (!empty($error) )
<div class="ErrorMsg">{{$error}}</div>
@endif


<form action="{{ url('pedidos2/ordenf_crear/'.$order_id.'?paso=1') }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
<div class="Fila"><label>Folio</label><input type="text" name="code" class="form-control" maxlength="24" /></div>

    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        echo "<option value='$k'>$v</option>";
        }
        ?>
    </select>
    </div>

    <div class="Fila"><div class="monitor"></div></div>

    <div class="Fila" rel='archivo'><label>Documento</label><input type="file" name="document" class="form-control" /> </div>

 
    
    <div class="Fila" id="rowContinuar" style="display:none"><input type="submit" name="sb" class="form-control" value="Continuar" /> </div>

</aside>


</form>