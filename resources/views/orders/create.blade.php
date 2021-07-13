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
                                    <div class="nav-tabs-navigation" style="margin-left: 10px;">
                                        <div class="nav-tabs-wrapper">
                                            <ul class="nav nav-tabs" data-tabs="tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active show" href="#basic" data-toggle="tab">
                                                    <i class="material-icons">home</i> Información Básica
                                                    <div class="ripple-container"></div>
                                                    <div class="ripple-container"></div></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#deliverys" data-toggle="tab">
                                                    <i class="material-icons">local_shipping</i> Entregas (opcional)
                                                    <div class="ripple-container"></div>
                                                    <div class="ripple-container"></div></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="basic">
                                                <div class="row">
                                                    <label class="col-sm-2 col-form-label">Crédito</label>
                                                    <div class="col-sm-7">
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

                                                <div class="row">
                                                    <label class="col-sm-2 col-form-label">No. de facturo a folio</label>
                                                    <div class="col-sm-7">
                                                        <div class="form-group bmd-form-group is-filled">
                                                            <input class="form-control" name="invoice" id="input-name" type="text" placeholder="Factura/folio" value="" required="true" aria-required="true">
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
                                            </div>
                                            <div class="tab-pane" id="deliverys">
                                                <div class="row">
                                                    <label class="col-sm-2 col-form-label">Dirección</label>
                                                    <div class="col-sm-7">
                                                        <div class="form-group bmd-form-group is-filled">
                                                            <input class="form-control" name="address" id="input-address" type="text" placeholder="Dirección de entrega" value="" required="true" aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="col-ms-3">
                                                        <a class="btn btn-sm btn-primary" href="#" onclick="AgregarCampos();">Agregar destinatario</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-2 col-form-label">Contacto</label>
                                                    <div class="col-sm-5">
                                                        <div class="form-group bmd-form-group is-filled">
                                                            <input class="form-control" name="contact" id="input-contact" type="text" placeholder="Nombre de quien recibe" value="" required="true" aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group bmd-form-group is-filled">
                                                            <input class="form-control" name="phone" id="input-phone" type="text" placeholder="Teléfono de contacto" value="" required="true" aria-required="true">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div id="campos">

                                            </div>
                                        </div>
                                    </div>
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

<script type="text/javascript">
    var nextinput = 0;
    function AgregarCampos(){
    nextinput++;
    campo = ``;
    if(nextinput < 4){
        campo = `
        <div class="row">
            <label class="col-sm-2 col-form-label">Dirección</label>
            <div class="col-sm-7">
                <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="address" id="input-address" type="text" placeholder="Dirección de entrega" value="" required="true" aria-required="true">
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 col-form-label">Contacto</label>
            <div class="col-sm-5">
                <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="contact" id="input-contact" type="text" placeholder="Nombre de quien recibe" value="" required="true" aria-required="true">
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="phone" id="input-phone" type="text" placeholder="Teléfono de contacto" value="" required="true" aria-required="true">
                </div>
            </div>
        </div>
        `;
    $("#campos").append(campo);
    }
    }
</script>

@endpush

