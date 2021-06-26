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
                                    <label class="col-sm-2 col-form-label">No. de facturo a folio</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="invoice" id="input-name" type="text" placeholder="Factura/folio" value="{{ $order->invoice }}" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Clave de cliente</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="client" id="input-name" type="text" placeholder="Identificador del cliente" value="{{ $order->client }}" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="statAnt" value="{{ $order->status->id }}">
                                <div class="row" {{ $department->name == "Ventas" ? 'hidden' : ''}}>
                                    <label class="col-sm-2 col-form-label">Estatus</label>
                                    <div class="col-sm-7">
                                        <select name="status_id" id="status_id" class="form-control" onchange="actualizar(this)"" >
                                            <option value="1" selected><b>{{ $order->status->name }}</b></option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
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

@endpush

