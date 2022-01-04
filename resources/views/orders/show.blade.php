@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4>Pedido {{ $order->invoice }} - {{ $order->delete ? 'Archivada' : 'Activa' }}</h4>
                        <p class="card-category"> Detalles e historial</p>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                arrow_back
                            </span>

                            Regresar
                        </a>
                        @if ( !isset($order->cancelation) && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Fabricación" || $department->name == "Flotilla" || $department->name == "Compras"  || $department->name == "Ventas") )
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-primary">
                                <span class="material-icons">
                                    upgrade
                                </span>
                                Actualizar pedido
                            </a>
                        @endif
                        @if ( $role->name == "Administrador" )
                            <form action="{{ route('orders.destroy', $order->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <span class="material-icons">
                                        archive
                                    </span>
                                    Archivar
                                </button>
                            </form>
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
                    <label class="col-sm-2 col-form-label">Sucursal</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="office" id="input-name" type="text" placeholder="Sucursal" value="{{ $order->office ? $order->office : '' }}"  disabled="true">
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
                            <input class="form-control" name="invoice_number" id="input-name" type="text" placeholder="Sin Factura ligada" value="{{ $order->invoice_number ? $order->invoice_number : '' }}"  disabled="true">
                        </div>
                    </div>
                </div>
                {{-- Si Orden de Requisición --}}
                @if ( $order->purchaseorder )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Orden de Requisición</label>
                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group is-filled">
                                <input class="form-control" name="purchaseorder" id="purchaseorder" type="text" placeholder="Orden de Requisición" value="{{ $order->purchaseorder->number ? $order->purchaseorder->number : NULL }}"  disabled="true">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input name="iscovered" class="form-check-input" type="checkbox" {{ $order->purchaseorder->iscovered ? 'checked' : '' }}  disabled="true">
                                    Se cubrió OC?
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Fin si Orden de Requisición --}}

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
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Bitácora</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        {{-- <div class="card-header card-header-primary">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                                    <h4 class="card-title ">Bitácora</h4>
                                                    <p class="card-category"> Todos los movimientosa</p>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="card-body">
                                            <div class="table-responsive ">
                                                <table class="table" id="bitacora">
                                                    <thead>
                                                        <tr>
                                                            <th width="150px">Fecha</th>
                                                            <th width="150px">Usuario</th>
                                                            <th width="150px">Departamento</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($logs as $log)
                                                            <tr>
                                                                <td>{{ $log->created_at->toDateTimeString() }}</td>
                                                                <td>{{ $log->user->name }}</td>
                                                                <td>{{ $log->department->name }}</td>
                                                                <td>{{ $log->action }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Mostrar Orden de Requisición --}}
                {{-- @if ( $order->purchaseorder && ($role->name == "Administrador" || $department->name == "Compras") ) --}}
                @if ( $order->purchaseorder )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Orden de Requisición</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-5">
                                    @if ( $order->purchaseorder->document )
                                        <a data-toggle="modal" data-target="#purchaseorder{{ $order->purchaseorder->id }}">
                                            @if ( pathinfo($order->purchaseorder->document, PATHINFO_EXTENSION) == "png" )
                                                <img src="{{ asset('storage') }}/{{ $order->purchaseorder->document }}" alt="" style="width: 100%">
                                            @else
                                                <embed src="{{ asset('storage') }}/{{ $order->purchaseorder->document }}" alt="" style="width: 100%">
                                            @endif
                                            <p>Factura: {{ $order->purchaseorder->number }}</p>
                                        </a>
                                    @endif
                                </div>
                                <div class="col-sm-5">
                                    @if ( $order->purchaseorder->requisition )
                                        <a data-toggle="modal" data-target="#purchaseorder{{ $order->purchaseorder->id }}">
                                            @if ( pathinfo($order->purchaseorder->requisition, PATHINFO_EXTENSION) == "png" )
                                                <img src="{{ asset('storage') }}/{{ $order->purchaseorder->requisition }}" alt="" style="width: 100%">
                                            @else
                                                <embed src="{{ asset('storage') }}/{{ $order->purchaseorder->requisition }}" alt="" style="width: 100%">
                                            @endif
                                            <p>Requisición</p>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="modal fade bd-example-modal-lge" id="purchaseorder{{ $order->purchaseorder->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        @if ( pathinfo($order->purchaseorder->document, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $order->purchaseorder->document }}" alt="" style="width: 100%">
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $order->purchaseorder->document }}" alt="" style="width: 100%; height: 600px;">
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Fin de mostrar Orden de Requisición --}}

                {{-- Mostrar orden de fabricación --}}
                {{-- @if ( $order->purchaseorder && ($role->name == "Administrador" || $department->name == "Compras") ) --}}
                @if ( $order->manufacturingorder )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Orden de Fabricación</label>
                        <div class="col-sm-10">
                            <div class="col-sm-5">
                                @if ( $order->manufacturingorder->document )
                                    <a data-toggle="modal" data-target="#manufacturing{{ $order->manufacturingorder->id }}">
                                        @if ( pathinfo($order->manufacturingorder->document, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $order->manufacturingorder->document }}" alt="" style="width: 100%">
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $order->manufacturingorder->document }}" alt="" style="width: 100%">
                                        @endif
                                        <p>Orden de fabricación: {{ $order->manufacturingorder->number }}</p>
                                    </a>
                                @endif
                            </div>
                            <div class="modal fade bd-example-modal-lge" id="manufacturing{{ $order->manufacturingorder->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        @if ( pathinfo($order->manufacturingorder->document, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $order->manufacturingorder->document }}" alt="" style="width: 100%">
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $order->manufacturingorder->document }}" alt="" style="width: 100%; height: 600px;">
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Fin de mostrar Orden de Requisición --}}

                {{-- Mostrar evidencia de material terminado --}}
                @if ( $order->shipments->count() > 0 )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Evidencia de salida del material</label>
                        <div class="col-sm-10"></div>
                            @foreach ($order->shipments as $shipment)
                                <div class="col-sm-4">
                                    <a data-toggle="modal" data-target="#shipments{{ $shipment->id }}">
                                        @if ( pathinfo($shipment->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $shipment->file }}" alt="" style="width: 100%">
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $shipment->file }}" alt="" style="width: 100%">
                                        @endif

                                        <p>{{ $shipment->file }}</p>
                                    </a>
                                </div>
                                <div class="modal fade bd-example-modal-lge" id="shipments{{ $shipment->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            @if ( pathinfo($shipment->file, PATHINFO_EXTENSION) == "png" )
                                                <img src="{{ asset('storage') }}/{{ $shipment->file }}" alt="" style="width: 100%">
                                            @else
                                                <embed src="{{ asset('storage') }}/{{ $shipment->file }}" alt="" style="width: 100%; height: 600px;">
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Fin de mostrar evidencia de material terminado --}}

                {{-- Entregado --}}
                @if ( $order->pictures->count() > 0 )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Evidencia</label>
                        <div class="col-sm-10">
                            @foreach ($order->pictures as $picture)
                                <div class="col-sm-4">
                                    <a data-toggle="modal" data-target="#cancelation{{ $picture->id }}">
                                        @php
                                            $ext = pathinfo($picture->picture, PATHINFO_EXTENSION)
                                        @endphp
                                        <p>{{ $picture->picture }}</p>
                                        {{-- @if ( pathinfo($picture->picture, PATHINFO_EXTENSION) == '.pdf' )
                                            <h1>pdf</h1>
                                        @else
                                            <h1>imagen</h1>
                                        @endif --}}
                                        <embed src="{{ asset('storage') }}/{{ $picture->picture }}" alt="" style="width: 100%">
                                        <p>{{ $picture->picture }}</p>
                                    </a>
                                </div>
                                <div class="modal fade bd-example-modal-lge" id="cancelation{{ $picture->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <embed src="{{ asset('storage') }}/{{ $picture->picture }}" alt="" style="width: 100%; height: 600px;">
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                {{-- <div class="card" style="width: 100%;">
                                    <img class="card-img-top" src="{{ asset('storage') }}/{{ $picture->picture }}" alt="Card image cap">
                                    <div class="card-body">
                                    <p class="card-text">Foto subida el {{ $picture->created_at->isoFormat('MMM Do YY') }} por {{ $picture->user->name }}</p>
                                    </div>
                                </div>                                 --}}
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Fin de entregado --}}

                {{-- Evidencias de cancelación --}}
                    @if ( $order->cancelation )
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Cancelaciónes</label>
                            <div class="col-sm-10">
                                <p>Evidencias</p>
                                @foreach ($order->cancelation->evidences as $evidence)
                                    <div class="col-sm-5">
                                        <a data-toggle="modal" data-target="#cancelation{{ $evidence->id }}">
                                            @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                                <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                            @else
                                                <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                            @endif

                                            <p>{{ $evidence->file }}</p>
                                        </a>
                                    </div>
                                    <div class="modal fade bd-example-modal-lge" id="cancelation{{ $evidence->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                                    <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                                @else
                                                    <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 600px;">
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    {{-- <div class="card" style="width: 100%;">
                                        <embed src="{{ asset('storage') }}/{{ $evidence->file }}" type="application/pdf" width="100%" height="400px" />
                                        <div class="card-body">
                                        <p class="card-text">Archivo: <a href="{{ asset('storage') }}/{{ $evidence->file }}" target="_blank">{{ $file->file }}</a> cancelado por: {{ $order->cancelation->reason->reason }}</p>
                                        </div>
                                    </div> --}}
                                @endforeach
                                <p>Notas</p>
                                @foreach ($order->cancelation->repayments as $repayment)
                                    <div class="col-sm-5">
                                        <a data-toggle="modal" data-target="#repayment{{ $repayment->id }}">
                                            <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%">
                                            <p>{{ $repayment->file }}</p>
                                        </a>
                                    </div>
                                    <div class="modal fade bd-example-modal-lge" id="repayment{{ $repayment->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 600px;">
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    {{-- <div class="card" style="width: 100%;">
                                        <embed src="{{ asset('storage') }}/{{ $repayment->file }}" type="application/pdf" width="100%" height="400px" />
                                        <div class="card-body">
                                        <p class="card-text">Archivo: <a href="{{ asset('storage') }}/{{ $evidence->file }}" target="_blank">{{ $file->file }}</a> cancelado por: {{ $order->cancelation->reason->reason }}</p>
                                        </div>
                                    </div> --}}
                                @endforeach
                            </div>
                        </div>
                    @endif
                {{-- Fin de evidencias de cancelación --}}

                {{-- Evidencias de Rebilling --}}
                @if ( $order->rebilling )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Refacturaciones</label>
                        <div class="col-sm-10">
                            <p>Evidencias</p>
                            @foreach ($order->rebilling->evidences as $evidence)
                                <div class="col-sm-5">
                                    <a data-toggle="modal" data-target="#rebilling{{ $evidence->id }}">
                                        @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                        @endif
                                        <p>{{ $evidence->file }}</p>
                                    </a>
                                </div>
                                <div class="modal fade bd-example-modal-lge" id="rebilling{{ $evidence->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                                <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                            @else
                                                <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 600px;">
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                {{-- <div class="card" style="width: 100%;">
                                    <embed src="{{ asset('storage') }}/{{ $evidence->file }}" type="application/pdf" width="100%" height="400px" />
                                    <div class="card-body">
                                    <p class="card-text">Archivo: <a href="{{ asset('storage') }}/{{ $evidence->file }}" target="_blank">{{ $file->file }}</a> cancelado por: {{ $order->cancelation->reason->reason }}</p>
                                    </div>
                                </div> --}}
                            @endforeach
                            <p>Notas</p>
                            @foreach ($order->rebilling->repayments as $repayment)
                                <div class="col-sm-5">
                                    <a data-toggle="modal" data-target="#repayment{{ $repayment->id }}">
                                        <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%">
                                        <p>{{ $repayment->file }}</p>
                                    </a>
                                </div>
                                <div class="modal fade bd-example-modal-lge" id="repayment{{ $repayment->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 600px;">
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                {{-- <div class="card" style="width: 100%;">
                                    <embed src="{{ asset('storage') }}/{{ $repayment->file }}" type="application/pdf" width="100%" height="400px" />
                                    <div class="card-body">
                                    <p class="card-text">Archivo: <a href="{{ asset('storage') }}/{{ $evidence->file }}" target="_blank">{{ $file->file }}</a> cancelado por: {{ $order->cancelation->reason->reason }}</p>
                                    </div>
                                </div> --}}
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Fin de Rebilling --}}

                {{-- Evidencias de Devoluciones --}}
                @if ( $order->debolution )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Devoluciones</label>
                        <div class="col-sm-10">
                            <p>Evidencias</p>
                            @foreach ($order->debolution->evidences as $evidence)
                                <div class="col-sm-5">
                                    <a data-toggle="modal" data-target="#debolution{{ $evidence->id }}">
                                        @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                        @endif
                                        <p>{{ $evidence->file }}</p>
                                    </a>
                                </div>
                                <div class="modal fade bd-example-modal-lge" id="debolution{{ $evidence->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                                <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                            @else
                                                <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 600px;">
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                {{-- <div class="card" style="width: 100%;">
                                    <embed src="{{ asset('storage') }}/{{ $evidence->file }}" type="application/pdf" width="100%" height="400px" />
                                    <div class="card-body">
                                    <p class="card-text">Archivo: <a href="{{ asset('storage') }}/{{ $evidence->file }}" target="_blank">{{ $file->file }}</a> cancelado por: {{ $order->cancelation->reason->reason }}</p>
                                    </div>
                                </div> --}}
                            @endforeach
                            <p>Notas</p>
                            @foreach ($order->debolution->repayments as $repayment)
                                <div class="col-sm-5">
                                    <a data-toggle="modal" data-target="#repayment{{ $repayment->id }}">
                                        <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%">
                                        <p>{{ $repayment->file }}</p>
                                    </a>
                                </div>
                                <div class="modal fade bd-example-modal-lge" id="repayment{{ $repayment->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        {{-- <h5 class="modal-title" id="exampleModalLabel">{{ $partial->invoice }}</h5> --}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 600px;">
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                {{-- <div class="card" style="width: 100%;">
                                    <embed src="{{ asset('storage') }}/{{ $repayment->file }}" type="application/pdf" width="100%" height="400px" />
                                    <div class="card-body">
                                    <p class="card-text">Archivo: <a href="{{ asset('storage') }}/{{ $evidence->file }}" target="_blank">{{ $file->file }}</a> cancelado por: {{ $order->cancelation->reason->reason }}</p>
                                    </div>
                                </div> --}}
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Fin de devoluciones --}}


                {{-- Parciales --}}
                @if ( $order->partials->count() > 0 )
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Parciales</label>
                        <div class="col-sm-10">
                            <table class="table data-table" id="orders">
                                <thead>
                                    <tr>
                                        <th >Folio</th>
                                        <th >Creación</th>
                                        <th >Estatus</th>
                                        <th class="text-center">Acciones</th>
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
                                                    <p >{{ $partial->created_at->isoFormat('MMM Do YY') }}</p>
                                                {{-- </a> --}}
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('partials.show', $partial->id) }}" class="btn btn-primary btn-link btn-sm"> --}}
                                                    <p >{{ $partial->status->name }}</p>
                                                {{-- </a> --}}
                                            </td>
                                            {{-- Acciones --}}
                                            <td class="text-center">
                                                @if ( $partial->status->name == 'En ruta' )
                                                    <span class="material-icons">
                                                        local_shipping
                                                    </span>
                                                @endif
                                                @if ( $partial->status->name == 'Entregado' )
                                                    <a type="button" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="modal" data-target="#partialImg{{ $partial->id }}">
                                                        <span class="material-icons">
                                                            image
                                                        </span>
                                                    </a>
                                                    <div class="modal fade" id="partialImg{{ $partial->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Parcial: {{ $partial->invoice }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @foreach ($partial->pictures as $picture)
                                                                    <div class="card" style="width: 100%;">
                                                                        <img class="card-img-top" src="{{ asset('storage') }}/{{ $picture->picture }}" alt="Card image cap">
                                                                        <div class="card-body">
                                                                        <p class="card-text">Entregado: {{ $picture->created_at->toDateTimeString() }} por {{ $picture->user->name }}</p>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
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

                {{-- Subir evidencia de ruta --}}
                @if ( $order->status_id == 5 && ($role->name == "Administrador" || $department->name == "Embarques") )
                    <form method="POST" action="{{ route('shipments.evidence') }}">
                        @csrf
                        @method('get')
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-7">
                                <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                <button type="submit" class="btn btn-sm btn-alert">
                                    <span class="material-icons">
                                        photo_camera
                                    </span>
                                    /
                                    <span class="material-icons">
                                        picture_as_pdf
                                    </span>
                                    Subir evidencia de material terminado
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
                {{-- Fin de subir evidencia de ruta --}}

                {{-- Subir foto de entregado --}}
                @if ( ($order->status_id == 5 || $order->status_id == 6 ) && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla" || $department->name == "Ventas") )

                    {{-- <p># de parciales: {{ $order->partials->count() }}</p> --}}
                    @php
                        $e = 0;
                    @endphp
                    @foreach ( $order->partials as $partial )
                        @if ( $partial->status->name == 'Entregado')
                            @php
                                $e++;
                            @endphp
                        @endif
                        {{-- <p>Parciales entregados: {{ $e }}</p> --}}
                    @endforeach

                    @if ( $order->partials->count() != $e )
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-7">
                                <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                <a href="{{ route('orders.edit', $order->id) }}" type="submit" class="btn btn-sm btn-alert">
                                    <span class="material-icons">
                                        info
                                    </span>
                                    Para subir evidencia final de entregado, se requieren cubrir todos los {{ $order->partials->count() }} parciales
                                </a>
                            </div>
                        </div>
                    @else
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
                @endif
                {{-- Fin subir foto de entregago --}}
                {{-- @if ( ($order->status_id == 7 || $order->status_id == 8) && $role->name == "Administrador" && !isset($order->cancelation) ) --}}

                {{-- Botones Cancelaciones --}}
                    {{-- Boton Subir foto de nota de devolución o crédito --}}
                    @if ( ($order->status_id == 7) && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla" || $department->name == "Ventas") )
                        <form method="POST" action="{{ route('cancelations.repayment') }}">
                            @csrf
                            @method('get')
                            <div class="row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                    @if ($order->cancelation->repayments->count() == 0 )
                                        <button type="submit" class="btn btn-sm btn-danger">
                                    @endif
                                    @if ($order->cancelation->repayments->count() > 0 )
                                        <button type="submit" class="btn btn-sm btn-primary">
                                    @endif
                                        <span class="material-icons">
                                            photo_camera
                                        </span>
                                        Subir foto de nota de devolución / crédito
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    {{-- Fin Boton Subir foto de nota de devolución o crédito --}}
                    {{-- Botón suvir evidencia de cancelación --}}
                    @if ( $order->status_id == 7 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla" || $department->name == "Ventas") )
                        <form method="POST" action="{{ route('cancelations.evidence') }}">
                            @csrf
                            @method('get')
                            <div class="row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                    @if ( $order->cancelation->evidences->count() > 0 )
                                        <input type="hidden" name="oldCancelation" value="true" class="form-control">
                                    @endif
                                    @if ( $order->cancelation->evidences->count() > 0 )
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                description
                                            </span>
                                            Subir otra evidencia
                                    @endif
                                    @if ( $order->cancelation->evidences->count() == 0 )
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <span class="material-icons">
                                                description
                                            </span>
                                            Evidencias Adiocionales
                                    @endif

                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    {{-- Fin Botón suvir evidencia de cancelación --}}
                {{-- Fin de botones cancelaciónes --}}


                {{-- Botones Rebilling --}}
                    {{-- Boton Subir foto de nota de devolución o crédito --}}
                    @if ( ($order->status_id == 8) && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla" || $department->name == "Ventas") )
                        <form method="POST" action="{{ route('rebillings.repayment') }}">
                            @csrf
                            @method('get')
                            <div class="row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                    {{-- @if ($order->rebilling->repayments->count() == 0 )
                                        <button type="submit" class="btn btn-sm btn-danger">
                                    @endif
                                    @if ($order->rebilling->repayments->count() > 0 )
                                        <button type="submit" class="btn btn-sm btn-primary">
                                    @endif --}}
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <span class="material-icons">
                                            photo_camera
                                        </span>
                                        Subir foto de nota de devolución / crédito
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    {{-- Fin Boton Subir foto de nota de devolución o crédito --}}
                    {{-- Botón suvir evidencia de REBILLING --}}
                    @if ( $order->status_id == 8 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla" || $department->name == "Ventas") )
                        {{-- Separar las rutas: cancel.evidence, cancel.repayment, rebillings.evidence, rebilling.repayment... etc. --}}
                        <form method="POST" action="{{ route('rebillings.evidence') }}">
                            @csrf
                            @method('get')
                            <div class="row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                    {{-- @if ( $order->rebilling->evidences->count() > 0 )
                                        <input type="hidden" name="oldCancelation" value="true" class="form-control">
                                    @endif
                                    @if ( $order->rebilling->evidences->count() > 0 )
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                description
                                            </span>
                                            Subir otra evidencia
                                    @endif
                                    @if ( $order->rebilling->evidences->count() == 0 )
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <span class="material-icons">
                                                description
                                            </span>
                                            Evidencias Adiocionales
                                    @endif --}}
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <span class="material-icons">
                                            description
                                        </span>
                                        Evidencias Adiocionales

                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    {{-- Fin Botón suvir evidencia de cancelación --}}
                {{-- Fin de botones rebilling --}}

                {{-- Botones Devolución --}}
                    {{-- Boton Subir foto de nota de devolución o crédito --}}
                    @if ( ($order->status_id == 9) && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla" || $department->name == "Ventas") )
                        <form method="POST" action="{{ route('debolutions.repayment') }}">
                            @csrf
                            @method('get')
                            <div class="row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                    @if ($order->debolution->repayments->count() == 0 )
                                        <button type="submit" class="btn btn-sm btn-danger">
                                    @endif
                                    @if ($order->debolution->repayments->count() > 0 )
                                        <button type="submit" class="btn btn-sm btn-primary">
                                    @endif
                                        <span class="material-icons">
                                            photo_camera
                                        </span>
                                        Subir foto de nota de devolución / crédito
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    {{-- Fin Boton Subir foto de nota de devolución o crédito --}}
                    {{-- Botón suvir evidencia de Devolución --}}
                    @if ( $order->status_id == 9 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla" || $department->name == "Ventas") )
                        {{-- Separar las rutas: cancel.evidence, cancel.repayment, rebillings.evidence, rebilling.repayment... etc. --}}
                        <form method="POST" action="{{ route('debolutions.evidence') }}">
                            @csrf
                            @method('get')
                            <div class="row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="order" value="{{ $order->id }}" class="form-control">
                                    @if ( $order->debolution->evidences->count() > 0 )
                                        <input type="hidden" name="oldCancelation" value="true" class="form-control">
                                    @endif
                                    @if ( $order->debolution->evidences->count() > 0 )
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                description
                                            </span>
                                            Subir otra evidencia
                                    @endif
                                    @if ( $order->debolution->evidences->count() == 0 )
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <span class="material-icons">
                                                description
                                            </span>
                                            Evidencias Adiocionales
                                    @endif

                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    {{-- Fin Botón suvir evidencia de cancelación --}}
                {{-- Fin de botones devolución --}}
            </div>
          </div>
      </div>
    </div>
  </div>
@endsection

@push('js')

@endpush

