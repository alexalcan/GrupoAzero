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
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Creación de un nuevo pedido</h4>
                                        <p class="card-category">{{ auth()->user()->name }} / {{ $role->name }} / {{ $department->name }}</p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
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
                                    <label class="col-sm-2 col-form-label">No. de facturo a folio</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="invoice" id="input-name" type="text" placeholder="Factura/folio" value="" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Cliente</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="client" id="input-name" type="text" placeholder="Identificador del cliente" value="" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                @if ( $role->name == "Administrador")
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Rol</label>
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

