@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('orders.update', $order->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Actualizar pedido</h4>
                                        <p class="card-category">Historial de cambios</p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                arrow_back
                                            </span>
                                            Regresar
                                        </a>
                                        {{-- @if ( $order->status_id == 5 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla") )
                                            <form method="POST" action="{{ route('picture') }}">
                                            @csrf
                                            @method('get')
                                            <div class="row">
                                                <label class="text-right"></label>
                                                <div class="col-sm-12 text-right">
                                                    <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <span class="material-icons">
                                                            photo_camera
                                                        </span>
                                                        Subir foto de entregado
                                                    </button>
                                                </div>
                                            </div>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Favorito</label>
                                    <div class="col-sm-7">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="credit" id="credit" class="form-check-input" type="checkbox" {{ $order->credit ? 'checked' : '' }} >
                                                Pedido a crédito
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                {{-- Añadir Orden de compra --}}
                                @if ( !$order->purchaseorder && ($role->name == "Administrador" || $department->name == "Compras") )
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Orden de Compra (opcional)</label>
                                        <div class="col-sm-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input name="ocCheck" id="ocCheck" value="1" onchange="javascript:addOC()" class="form-check-input" type="checkbox" >
                                                    Ligar a orden de compra
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" id="purchaseSpace" style="display: none;">
                                            <div class="col-sm-6">
                                                <div class="form-group bmd-form-group is-filled">
                                                    <input class="form-control" name="purchase_order" id="purchase_order" type="text" placeholder="Orden de compra" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin añadir oren de comrpa --}}

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">No. de folio</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            @if ( $role->name == "Administrador" )
                                                <input class="form-control" name="invoice" id="invoice" type="text" placeholder="Folio" value="{{ $order->invoice }}" required="true" aria-required="true">
                                            @else
                                                <input class="form-control" name="invoice" id="invoice" type="text" placeholder="Folio" value="{{ $order->invoice }}" required="true" aria-required="true" disabled="true">
                                                <input class="form-control" name="invoice" id="invoice" type="hidden" placeholder="Folio" value="{{ $order->invoice }}" required="true" aria-required="true">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Factura</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled form-group{{ $errors->has('color') ? ' has-danger' : '' }}">
                                            <input class="form-control" name="invoice_number" id="invoice_number" type="text" placeholder="Factura" value="{{ isset($order->invoice_number) ? $order->invoice_number : NULL }}" requiered="true">

                                            @if ($errors->has('invoice_number'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('invoice_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if ( $order->purchaseorder && ($role->name == "Administrador" || $department->name == "Compras") )
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Orden de compra</label>
                                        <div class="col-sm-3">
                                            <div class="form-group bmd-form-group is-filled">
                                                <input class="form-control" name="purchaseorder" id="purchaseorder" type="text" placeholder="Orden de compra" value="{{ $order->purchaseorder->number ? $order->purchaseorder->number : NULL }}" >
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input name="iscovered" class="form-check-input" type="checkbox" {{ $order->purchaseorder->iscovered ? 'checked' : '' }} >
                                                    Se cubrió OC?
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <input type="file" name="document" class="form-control-file" id="document" accept="image/*,.pdf">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Clave de cliente</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            @if ( $role->name == "Administrador" )
                                                <input class="form-control" name="client" id="input-name" type="text" placeholder="Identificador del cliente" value="{{ $order->client }}" required="true" aria-required="true">
                                            @else
                                                <input class="form-control" name="client" id="input-name" type="text" placeholder="Identificador del cliente" value="{{ $order->client }}" required="true" aria-required="true" disabled="true">
                                                <input class="form-control" name="client" id="input-name" type="hidden" placeholder="Identificador del cliente" value="{{ $order->client }}" required="true" aria-required="true">
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                {{-- Estatus --}}
                                {{-- <div class="row" {{ $department->name == "Ventas" ? 'hidden' : ''}}> --}}
                                <div class="row" >
                                    <input type="hidden" name="statAnt" value="{{ $order->status->id }}">
                                    <label class="col-sm-2 col-form-label">Estatus</label>
                                    <div class="col-sm-7">
                                        <select name="status_id" id="status_id" class="form-control" onchange="ShowSelected(this)"" {{ ($order->status->id == 7 || $order->status->id == 8) ? 'disabled="true"' : '' }} >
                                            <option value="{{ $order->status->id }}" selected><b>{{ $order->status->name }}</b></option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- Fin de estatus --}}

                                {{-- Opcional al cancelar --}}
                                <div class="row" id="cancelReason" style="display: none">
                                    <div class="row col-sm-12">
                                        <label class="col-sm-2 col-form-label">
                                            <span class="material-icons">
                                                warning
                                            </span>
                                            Razón de cancelación
                                        </label>
                                        <div class="col-sm-5">
                                            <div class="form-group bmd-form-group is-filled">
                                                <select name="cancel_reason_id" id="reason_id" class="form-control">
                                                    <option value="1" selected><b>Selecciona una razón...</b></option>
                                                    @foreach ($reasons as $reason)
                                                        <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="">
                                                <label for="picture">Fotografía o PDF</label>
                                                <input type="file" name="cancelation" class="form-control-file" id="picture" accept="image/*,.pdf" capture="camera">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin opcional cancelar --}}
                                {{-- Opcional al refacturación --}}
                                <div class="row" id="refactReason" style="display: none">
                                    <div class="row col-sm-12">
                                        <label class="col-sm-2 col-form-label">
                                            <span class="material-icons">
                                                warning
                                            </span>
                                            Razón de la refacturación
                                        </label>
                                        <div class="col-sm-5">
                                            <div class="form-group bmd-form-group is-filled">
                                                <select name="refact_reason_id" id="reason_id" class="form-control">
                                                    <option value="1" selected><b>Selecciona una razón...</b></option>
                                                    @foreach ($reasons as $reason)
                                                        <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="">
                                                <label for="picture">Fotografía o PDF</label>
                                                <input type="file" name="rebilling" class="form-control-file" id="picture" accept="image/*,.pdf" capture="camera">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin opcional refacturación --}}
                                {{-- Opcional al devolución --}}
                                <div class="row" id="devolReason" style="display: none">
                                    <div class="row col-sm-12">
                                        <label class="col-sm-2 col-form-label">
                                            <span class="material-icons">
                                                warning
                                            </span>
                                            Razón de la devolución
                                        </label>
                                        <div class="col-sm-5">
                                            <div class="form-group bmd-form-group is-filled">
                                                <select name="debolution_reason_id" id="reason_id" class="form-control">
                                                    <option value="1" selected><b>Selecciona una razón...</b></option>
                                                    @foreach ($reasons as $reason)
                                                        <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="">
                                                <label for="picture">Fotografía o PDF del material</label>
                                                <input type="file" name="debolution" class="form-control-file" id="picture" accept="image/*,.pdf" capture="camera">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin opcional devolución --}}
                                {{-- Opcional al Orden de fabricación --}}
                                @if ( $order->manufacturingorder )
                                    <div class="row" >
                                        <div class="row col-sm-12">
                                            <label class="col-sm-2 col-form-label">
                                                Orden de Fabricación
                                            </label>
                                            <div class="col-sm-5">
                                                <div class="form-group bmd-form-group is-filled">
                                                    <input class="form-control" name="manufacturingOrder" id="manufacturingOrder" type="text" placeholder="Orden de Fabricación #" value="{{ $order->manufacturingorder->number }}" >
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="">
                                                    @if( $order->manufacturingorder->document )
                                                        <p>{{$order->manufacturingorder->document}}</p>
                                                    @else
                                                        <label for="picture">Subir orden de fabricación</label>
                                                        <input type="file" name="manufacturingFile" class="form-control-file" id="picture" accept="image/*,.pdf" capture="camera">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row" id="manufacturingSection" style="display: none">
                                        <div class="row col-sm-12">
                                            <label class="col-sm-2 col-form-label">
                                                Introduce Orden de Fabricación
                                            </label>
                                            <div class="col-sm-5">
                                                <div class="form-group bmd-form-group is-filled">
                                                    <input class="form-control" name="manufacturingOrder" id="manufacturingOrder" type="text" placeholder="Orden de Fabricación #" value="" >
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="">
                                                    <label for="picture">Subir orden de fabricación</label>
                                                    <input type="file" name="manufacturingFile" class="form-control-file" id="picture" accept="image/*,.pdf" capture="camera">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin opcional orden de fabricación --}}

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Notas</label>
                                    <div class="col-sm-10">
                                        <div class="form-group bmd-form-group is-filled">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="130px">Fecha</th>
                                                        <th width="200px">Usuario</th>
                                                        <th>Nota</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->notes as $note)
                                                        <tr>
                                                            <td>{{ $note->created_at->calendar() }}</td>
                                                            <td>{{ $note->user->name }}</td>
                                                            <td>{{ $note->note }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Nueva nota</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <textarea class="form-control" name="note" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                @if ( !$fav )
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Favorito</label>
                                        <div class="col-sm-7">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input name="favorite" class="form-check-input" type="checkbox" >
                                                    Hacer favorito
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-sm-2 col-form-label">
                                        <a class="btn btn-sm btn-primary" style="color: white" onclick="AgregarCampos();">
                                            <span class="material-icons">
                                                add
                                            </span>
                                            Parcial
                                        </a>
                                    </div>
                                    <div id="campos" class="col-sm-10">
                                    </div>
                                </div>
                                {{-- Parciales --}}
                                @if ( $order->partials )
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Parciales </label>
                                        <div class="col-sm-10">
                                            <table class="table data-table" id="orders">
                                                <thead>
                                                    <tr>
                                                        <th >Folio</th>
                                                        <th >Estatus</th>
                                                        <th class="text-center">Acciones</th>
                                                        {{-- @if ( $role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Ventas" || $department->name == "Fabricación")
                                                            <th width="50px">&nbsp;</th>
                                                        @endif --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->partials as $count => $partial)
                                                        <tr>
                                                            <td>
                                                                {{-- <a href="{{ route('partials.show', $partial->id) }}" class="btn btn-primary btn-link btn-sm"> --}}
                                                                    <p >{{ $partial->invoice }}</p>
                                                                {{-- </a> --}}
                                                            </td>
                                                            <td>
                                                                {{-- <a href="{{ route('partials.show', $partial->id) }}" class="btn btn-primary btn-link btn-sm"> --}}
                                                                    <input type="hidden" name="stPartID_{{ $count+1 }}" value="{{ $partial->id }}">
                                                                    <select name="stPartValue_{{ $count+1 }}" id="stPartValue_{{ $count+1 }}" class="form-control" >
                                                                        <option value="{{ $partial->status->id }}" selected><b>{{ $partial->status->name }}</b></option>
                                                                        @foreach ($statuses as $status)
                                                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                {{-- </a> --}}
                                                            </td>
                                                            {{-- <td class="text-right">
                                                                @if ( $role->name == "Administrador" || $department->name == "Ventas" || $department->name == "Embarques" || $department->name == "Fabricación")
                                                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                                                        <span class="material-icons">
                                                                            edit
                                                                        </span>
                                                                    </a>
                                                                @endif
                                                            </td> --}}
                                                            @if ( $partial->status->name == 'En ruta' )
                                                                <td  class="text-center">
                                                                    <div class="form-check">
                                                                        <input type="hidden" name="partImgID_{{ $count+1 }}" value="{{ $partial->id }}">
                                                                        <input type="file" name="partImg_{{ $count+1 }}" class="form-control-file" accept="image/*">
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            @if ( $partial->status->name == 'Entregado' )
                                                                <td  class="text-center">
                                                                    <div class="form-check">
                                                                        @if ( $partial->pictures->count() > 0 )
                                                                            <span class="material-icons">
                                                                                check
                                                                            </span>
                                                                        @else
                                                                            <input type="hidden" name="partImgID_{{ $count+1 }}" value="{{ $partial->id }}">
                                                                            <input type="file" name="partImg_{{ $count+1 }}" class="form-control-file" accept="image/*">
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin de parciales --}}
                                @php
                                    $p = $order->partials->count();
                                    $e = 0;
                                @endphp
                                @foreach ( $order->partials as $partial )
                                    @if ( $partial->status->name == 'Entregado')
                                        @php
                                            $e++;
                                        @endphp
                                    @endif
                                    {{-- <p>Parciales totales: {{ $p }}</p>
                                    <p>Parciales entregados: {{ $e }}</p> --}}
                                @endforeach


                            </div>
                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
{{-- <script type="text/javascript">
    function viewCancel() {
        element = document.getElementById("cancelReason");
        cancelReason = document.getElementById("cancelReason");
        if (invCheck.checked) {
            element.style.display='block';
        }
        else {
            element.style.display='none';
        }
    }
</script> --}}
<script type="text/javascript">
    function addOC() {
        element = document.getElementById("purchaseSpace");
        ocCheck = document.getElementById("ocCheck");
        if (ocCheck.checked) {
            element.style.display='block';
        }
        else {
            element.style.display='none';
        }
    }
</script>

<script type="text/javascript">
    function ShowSelected(){
        /* Para obtener el valor */
        var status_id = document.getElementById("status_id").value;
        // console.log(status_id);
        var credit = document.getElementById('credit').checked;
        console.log(credit);
        p = {{ $p }} // p para el # de parciales totales
        e = {{ $e }} // e para el número de parciales entregados

        if( status_id == 3 ){ // En fabricación
            element = document.getElementById("manufacturingSection");
            element.style.display='block';
            element = document.getElementById("refactReason");
            element.style.display='none';
            element = document.getElementById("devolReason");
            element.style.display='none';
        }
        if( status_id == 5 && credit == true ){
            alert('Para pedidos por cobrar o pedidos programados es necesario ligar a una factura');
            document.getElementById("invoice_number").placeholder = "Para pedidos a crédito se requiere un número de factura!";
            document.getElementById("invoice_number").requiered = true;
            document.getElementById("invoice_number").focus();

            element = document.getElementById("manufacturingSection");
            element.style.display='none';
            element = document.getElementById("cancelReason");
            element.style.display='none';
            element = document.getElementById("refactReason");
            element.style.display='none';
        }
        if( status_id == 6 && p != e ){
            alert('No puedes marcar de entregado el pedido hasta entregar cada parcial');
        }
        if( status_id == 7 ){ // Cancelado
            element = document.getElementById("cancelReason");
            element.style.display='block';
            element = document.getElementById("refactReason");
            element.style.display='none';
            element = document.getElementById("devolReason");
            element.style.display='none';
            element = document.getElementById("manufacturingSection");
            element.style.display='none';
        }
        if( status_id == 8 ){ // Refacturación
            element = document.getElementById("refactReason");
            element.style.display='block';
            element = document.getElementById("cancelReason");
            element.style.display='none';
            element = document.getElementById("devolReason");
            element.style.display='none';
            element = document.getElementById("manufacturingSection");
            element.style.display='none';
        }
        if( status_id == 9 ){ // Devolución
            element = document.getElementById("devolReason");
            element.style.display='block';
            element = document.getElementById("cancelReason");
            element.style.display='none';
            element = document.getElementById("refactReason");
            element.style.display='none';
            element = document.getElementById("manufacturingSection");
            element.style.display='none';
        }
        if( status_id == 1 || status_id == 2 || status_id == 4 || status_id == 6 ){
            element = document.getElementById("cancelReason");
            element.style.display='none';
            element = document.getElementById("refactReason");
            element.style.display='none';
            element = document.getElementById("manufacturingSection");
            element.style.display='none';
        }
        /* Para obtener el texto */
        // var combo = document.getElementById("status_id");
        // var selected = combo.options[combo.selectedIndex].text;
        // console.log(selected);

    }
</script>

<script type="text/javascript">
    var nextinput = {{ $order->partials->count() }};
    function AgregarCampos(){
        nextinput++;
        campo = ``;
        if(nextinput < 6){
            campo = `
            <div class="col-sm-8">
                <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="folio`+nextinput+`" id="input-address" type="text" placeholder="Folio `+nextinput+`" value="" required="true" aria-required="true">
                </div>
            </div>
            <div class="col-sm-6">
                <select name="fol_status`+nextinput+`" id="status_id" class="form-control" onchange="actualizar(this)"">
                    <option value="1" selected><b>El status inicial es "Recibido"</b></option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            `;
        $("#campos").append(campo);
        }
    }
</script>

@endpush

