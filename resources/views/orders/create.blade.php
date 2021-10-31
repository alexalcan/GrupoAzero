@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('orders.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Creación de un nuevo pedido</h4>
                                        <p class="card-category">{{ auth()->user()->name }} / {{ $role->name }} / {{ $department->name }}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                arrow_back
                                            </span>
                                            Regresar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Crédito</label>
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="credit" class="form-check-input" type="checkbox" >
                                                El pedido tendrá crédito
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                {{-- Añadir factura --}}
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Ligar a Factura (opcional)</label>
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="invCheck" id="invCheck" value="1" onchange="javascript:addInvoice()" class="form-check-input" type="checkbox" >
                                                Ligar a una factura
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="invoiceSpace" style="display: none;">
                                        <div class="col-sm-6">
                                            <div class="form-group bmd-form-group is-filled">
                                                <input class="form-control" name="invoice_number" id="invoice_number" type="text" placeholder="Factura" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin añadir factura --}}
                                {{-- Añadir Orden de Requisición --}}
                                @if ( $role->name == "Administrador" )
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Orden de Requisición (opcional)</label>
                                        <div class="col-sm-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input name="ocCheck" id="ocCheck" value="1" onchange="javascript:addOC()" class="form-check-input" type="checkbox" >
                                                    Ligar a Orden de Requisición
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" id="purchaseSpace" style="display: none;">
                                            <div class="col-sm-6">
                                                <div class="form-group bmd-form-group is-filled">
                                                    <input class="form-control" name="purchase_order" id="purchase_order" type="text" placeholder="Orden de Requisición" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin añadir oren de comrpa --}}


                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Sucursal</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <select name="office" id="office" class="form-control" onchange="actualizar(this)"">
                                                <option value="San Pablo" selected>San Pablo</option>
                                                <option value="La Noria">La Noria</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <label class="col-sm-2 col-form-label">No. de folio</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="invoice" id="input-name" type="text" placeholder="Folio" value="" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Clave de cliente</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="client" id="input-name" type="text" placeholder="Identificador del cliente" value="" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                @if ( $role->name == "Administrador")
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Estatus</label>
                                        <div class="col-sm-7">
                                            <select name="status_id" id="status_id" class="form-control" onchange="actualizar(this)"">
                                                <option value="1" selected><b>El status inicial es "Recibido"</b></option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-7">
                                            <div class="form-group bmd-form-group is-filled">
                                                <input class="form-control" name="status_id" id="status_id" type="hidden" placeholder="" value="1" required="true" aria-required="true">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Nota</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <textarea class="form-control" name="note" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2 col-form-label">
                                        <a class="btn btn-sm btn-primary" style="color: white" onclick="AgregarCampos();">
                                            <span class="material-icons"> add</span>
                                                Parcial
                                            </a>
                                    </div>
                                    <div id="campos" class="col-sm-10">
                                    </div>
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

    {{-- <li id="rut'+nextinput+'">Correo:<input type="text" size="20" id="campo' + nextinput + '"&nbsp; name="campo' + nextinput + '"&nbsp; /></li>
        <input type="email" id="email' + nextinput + '"&nbsp; name="email' + nextinput + '" class="form-control" placeholder="Ej. usuario@ejemplo.com" /><br> --}}
@endsection

@push('js')
<script type="text/javascript">
    function addInvoice() {
        element = document.getElementById("invoiceSpace");
        invCheck = document.getElementById("invCheck");
        if (invCheck.checked) {
            element.style.display='block';
        }
        else {
            element.style.display='none';
        }
    }
</script>
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
    var nextinput = 0;
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

<script>
    function actualizar(opcion){
        console.log("Opción de rol",opcion.value);
        if(opcion.value == 3){
            let value = document.getElementById("inputDepartment").value= 2;
            let index = document.getElementById("inputDepartment").selectedIndex = 2;
            // let disable = document.getElementById("inputDepartment").disabled = true;
            console.log( document.getElementById("inputDepartment").value );
            console.log(value, index);
        }else{
            // document.getElementById("inputDepartment").disabled = false;
        }
    }
</script>

@endpush

