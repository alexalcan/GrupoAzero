<?php
$estatuses = ["5" => "En Puerta", "6"=>"Entregado"];
?>

@if (!empty($error) )
<div class="ErrorMsg">{{$error}}</div>
@endif


<form action="{{ url('pedidos2/ordenf_crear/'.$order_id.'?paso=1') }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
<div class="Fila"><label>Folio</label><input type="text" name="code" class="form-control" maxlength="24" /></div>



    <div class="Fila"><label>Documento</label><input type="file" name="document" class="form-control" /> </div>
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Continuar" /> </div>

</aside>


</form>