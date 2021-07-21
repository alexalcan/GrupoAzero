@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('orders.update', $order->id) }}">
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
                                                <input name="credit" class="form-check-input" type="checkbox" {{ $order->credit ? 'checked' : '' }} >
                                                Pedido a crédito
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

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
                                @if ( $role->name == "Administrador" || $department->name == "Compras" )
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Orden de compra</label>
                                        <div class="col-sm-7">
                                            <div class="form-group bmd-form-group is-filled">
                                                <input class="form-control" name="purchaseorder" id="purchaseorder" type="text" placeholder="Factura/folio" value="{{ $order->purchaseorder ? $order->purchaseorder->number : 'N/A' }}" >
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
                                <div class="row" {{ $department->name == "Ventas" ? 'hidden' : ''}}>
                                    <input type="hidden" name="statAnt" value="{{ $order->status->id }}">
                                    <label class="col-sm-2 col-form-label">Estatus</label>
                                    <div class="col-sm-7">
                                        <select name="status_id" id="status_id" class="form-control" onchange="ShowSelected(this)"" {{ ($order->status->id == 7 || $order->status->id == 8) ? 'disabled="true"' : '' }} >
                                            <option value="1" selected><b>{{ $order->status->name }}</b></option>
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
                                        <div class="col-sm-7">
                                            <div class="form-group bmd-form-group is-filled">
                                                <select name="reason_id" id="reason_id" class="form-control" required>
                                                    <option value="1" selected><b>Selecciona una razón...</b></option>
                                                    @foreach ($reasons as $reason)
                                                        <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
<script type="text/javascript">
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
</script>

<script type="text/javascript">
    function ShowSelected(){
        /* Para obtener el valor */
        var status_id = document.getElementById("status_id").value;
        console.log(status_id);

        if( status_id == 5 ){
            alert('Para pedidos por cobrar o pedidos programados es necesario ligar a una factura');
            document.getElementById("invoice_number").placeholder = "Para pedidos a crédito se requiere un número de factura!";
            document.getElementById("invoice_number").requiered = true;
            document.getElementById("invoice_number").focus();
        }
        if( status_id == 7 ){
            element = document.getElementById("cancelReason");
            element.style.display='block';

        }
        if( status_id != 7 ){
            element = document.getElementById("cancelReason");
            element.style.display='none';

        }
        /* Para obtener el texto */
        var combo = document.getElementById("status_id");
        var selected = combo.options[combo.selectedIndex].text;
        console.log(selected);

    }
</script>

@endpush

