<?php
$estatuses = [5 => "En Puerta", 6=>"Entregado"];
    if($user->role_id == 1 || in_array($user->department_id,[4,5])){
        $estatuses[4]="Fabricado";
    }
ksort($estatuses);


?>

@if (!empty($error) )
<div class="ErrorMsg">{{$error}}</div>
@endif


@if ($paso==1)
<form action="{{ url('pedidos2/smaterial_crear/'.$order_id.'?paso=1') }}" id="FSetAccion" method="post">
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

    <p class="hiddenDisclaimer"><b>Es Obligatorio subir evidencia en el siguiente paso. No continúe si no puede subir evidencia.</b></p>
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Continuar" /> </div>

</aside>


</form>


@else

<aside class="AccionForm">    

<div class="Fila"><label>Agregar Imágenes</label></div>

    <div id='atlSlot'  val="{{$smaterial->id}}" event="{{$smaterial->status_id}}"
        uploadto="{{ url('pedidos2/attachpost') }}" 
        listHref="{{ url('pedidos2/attachlist') }}">
    </div>  
    
    <div class="Fila" id="smTerminar" style="display: none;"><input type="button" name="parcialterminar" class="form-control" value="Terminar" /> </div>

</aside>

@endif