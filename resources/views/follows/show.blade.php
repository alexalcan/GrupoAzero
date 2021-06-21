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
                        @if ( $role->name == "Administrador" || $department->name == "Embarques" )
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
                    <label class="col-sm-2 col-form-label">Folio</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="name" id="input-name" type="text" placeholder="Name" value="{{ $order->invoice }}" disabled="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Cliente</label>
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
                @if ( $order->picture )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Evidencia</label>
                        <div class="col-sm-10">
                            <div class="card" style="width: 100%;">
                                <img class="card-img-top" src="{{ asset('storage') }}/{{ $order->picture->picture }}" alt="Card image cap">
                                <div class="card-body">
                                  <p class="card-text">Foto subida el {{ $order->picture->created_at->isoFormat('MMM Do YY') }} por {{ $order->picture->user->name }}</p>
                                </div>
                              </div>
                        </div>
                    </div>
                @endif
                @if ( $order->status_id == 5 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla") )
                    <form method="POST" action="{{ route('picture') }}">
                    @csrf
                    @method('get')
                    <div class="row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <span class="material-icons">
                                    photo_camera
                                </span>
                                Subir foto de entregado
                            </button>
                        </div>
                    </div>
                @endif
            </div>
          </div>
      </div>
    </div>
  </div>
@endsection

@push('js')

@endpush

