@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4>Pedido {{ $order->invoice }}</h4>
                        <p class="card-category"> Detalles e historial</p>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                arrow_back
                            </span>

                            Regresar
                        </a>
                        @if ( !isset($order->cancelation) && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Fabricación" || $department->name == "Flotilla") )
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-primary">
                                <span class="material-icons">
                                    upgrade
                                </span>
                                Actualizar pedido
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body ">
                <div class="row">
                    <label class="col-sm-2 col-form-label">Favorito</label>
                    <div class="col-sm-7">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input name="credit" class="form-check-input" type="checkbox" {{ $order->credit ? 'checked' : '' }} disabled="true">
                                Pedido a crédito
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 col-form-label">Folio</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="name" id="input-name" type="text" placeholder="Name" value="{{ $order->invoice }}" disabled="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Factura</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="invoice_number" id="input-name" type="text" placeholder="Factura/folio" value="{{ $order->invoice_number ? $order->invoice_number : 'N/A' }}"  disabled="true">
                        </div>
                    </div>
                </div>
                {{-- Si orden de compra --}}
                @if ( $order->purchaseorder )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Orden de compra</label>
                        <div class="col-sm-7">
                            <div class="form-group bmd-form-group is-filled">
                                <input class="form-control" name="purchaseorder" id="purchaseorder" type="text" placeholder="Factura/folio" value="{{ $order->purchaseorder ? $order->purchaseorder->number : 'N/A' }}"  disabled="true">
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Fin si orden de compra --}}

                <div class="row">
                    <label class="col-sm-2 col-form-label">Clave de cliente</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="client" id="input-name" type="text" placeholder="Identificador del cliente" value="{{ $order->client }}" required="true" aria-required="true" disabled="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Estatus</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="name" id="input-name" type="text" placeholder="Name" value="{{ $order->status->name }}" disabled="true">
                    </div>
                </div>
                @if ( $department->name != "Clientes")
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
                @endif

                @if ( $order->pictures )
                    <div class="row">
                        @foreach ($order->pictures as $picture)
                            <label class="col-sm-2 col-form-label">Evidencia</label>
                            <div class="col-sm-10">
                                <div class="card" style="width: 100%;">
                                    <img class="card-img-top" src="{{ asset('storage') }}/{{ $picture->picture }}" alt="Card image cap">
                                    <div class="card-body">
                                    <p class="card-text">Foto subida el {{ $picture->created_at->isoFormat('MMM Do YY') }} por {{ $picture->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ( $order->cancelation )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Cancelación</label>
                        <div class="col-sm-10">
                            @foreach ($order->cancelation->files as $file)
                                <div class="card" style="width: 100%;">
                                    <embed src="{{ asset('storage') }}/{{ $file->file }}" type="application/pdf" width="100%" height="400px" />
                                    <div class="card-body">
                                    <p class="card-text">Archivo: <a href="{{ asset('storage') }}/{{ $file->file }}" target="_blank">{{ $file->file }}</a> cancelado por: {{ $order->cancelation->reason->reason }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Parciales --}}
                @if ( $order->partials )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Parciales</label>
                        <div class="col-sm-10">
                            <table class="table data-table" id="orders">
                                <thead>
                                    <tr>
                                        <th >Folio</th>
                                        <th >Estatus</th>
                                        {{-- <th class="text-center">Acciones</th> --}}
                                        {{-- @if ( $role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Ventas" || $department->name == "Fabricación")
                                            <th width="50px">&nbsp;</th>
                                        @endif --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->partials as $partial)
                                        <tr>
                                            <td>
                                                {{-- <a href="{{ route('partials.show', $partial->id) }}" class="btn btn-primary btn-link btn-sm"> --}}
                                                    <p >{{ $partial->invoice }}</p>
                                                {{-- </a> --}}
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('partials.show', $partial->id) }}" class="btn btn-primary btn-link btn-sm"> --}}
                                                    <p >{{ $partial->status->name }}</p>
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
                                            {{-- @if ( $role->name == "Administrador" || $department->name == "Ventas" || $department->name == "Embarques" || $department->name == "Fabricación")
                                                <td>
                                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                                        <span class="material-icons">
                                                            edit
                                                        </span>
                                                    </a>
                                                </td>
                                            @endif --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                {{-- Fin de parciales --}}

                @if ( $order->status_id == 5 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla") )
                    <form method="POST" action="{{ route('picture') }}">
                        @csrf
                        @method('get')
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-7">
                                <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <span class="material-icons">
                                        photo_camera
                                    </span>
                                    Subir foto de entregado
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
                {{-- @if ( ($order->status_id == 7 || $order->status_id == 8) && $role->name == "Administrador" && !isset($order->cancelation) ) --}}
                @if ( ($order->status_id == 7 || $order->status_id == 8) && $role->name == "Administrador" )
                    <form method="POST" action="{{ route('picture') }}">
                        @csrf
                        @method('get')
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-7">
                                <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                @if ($order->pictures->count() == 0 )
                                    <button type="submit" class="btn btn-sm btn-danger">
                                @endif
                                @if ($order->pictures->count() > 0 )
                                    <button type="submit" class="btn btn-sm btn-primary">
                                @endif
                                    <span class="material-icons">
                                        photo_camera
                                    </span>
                                    Subir foto de nota de devolución crédito
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
                @if ( ($order->status_id == 7 || $order->status_id == 8) && $role->name == "Administrador" )
                    <form method="POST" action="{{ route('cancelation') }}">
                        @csrf
                        @method('get')
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-7">
                                <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                @if ( isset($order->cancelation) )
                                    <input type="hidden" name="oldCancelation" value="true" class="form-control">
                                @endif
                                @if ( isset($order->cancelation) )
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <span class="material-icons">
                                            description
                                        </span>
                                        Subir otra evidencia
                                @endif
                                @if ( !isset($order->cancelation) )
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <span class="material-icons">
                                            description
                                        </span>
                                        Nueva factura
                                @endif

                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
          </div>
      </div>
    </div>
  </div>
@endsection

@push('js')

@endpush

