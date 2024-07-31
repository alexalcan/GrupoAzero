<?php
$estatuses = [1=>"Elaborada"];
    if($user->role_id==1){
        $estatuses[4]="Cancelada";
    }
    if(in_array($user->department_id, [4,7]) || $user->role_id == 1){
        $estatuses[5]="En Puerta";
    }
    if(in_array($user->department_id, [4,7]) || $user->role_id == 1){
        $estatuses[6]="Entregada";
    }

?>

@if (!empty($error) )
<div class="ErrorMsg">{{$error}}</div>
@endif


<form action="{{ url('pedidos2/requisicion_crear/'.$order_id.'?paso=1') }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    <div class="ScrollModal">

    <div class="Fila"><label># Requisición</label><input type="text" name="number" class="form-control" maxlength="24" /></div>

    

    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        echo "<option value='$k'>$v</option>";
        }
        ?>
    </select>
    </div>


    <div class="Fila" id="rowFolioSM" style="display: none;">
        <label>Folio Salida de Materiales</label>
        <input type="text" name="code_smaterial" class="form-control" maxlength="24" />
    </div>



    @if ($user->role_id==1 || $user->department_id == 7)
    <div class="Fila"><label>Archivo Requisición</label>
        <div>
        <input type="file" name="requisition" class="form-control" /> 
        </div>
    </div>
    @endif
    </div>


    <div class="Fila" id="SubmitButtonRow"><input type="submit" name="sb" class="form-control" value="Continuar" /> </div>


</aside>


</form>