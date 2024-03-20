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
                                {{-- Hacer favorito --}}
                                
                                 
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
                                
                                
                                {{-- fin de hacer favorito --}}
                                {{-- Añadir Orden de Requisición --}}
                                @if ( !$order->purchaseorder && ($role->name == "Administrador" || $department->name == "Compras" || $department->name == "Ventas") )
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
                                {{-- Número de folio --}}
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
                                {{-- Fin número de folio --}}
                                {{-- Sucursal --}}
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Sucursal</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <select name="office" id="office" class="form-control">
                                                <option value="{{ $order->office }}" selected><b>{{ $order->office }}</b></option>
                                                <option value="San Pablo">San Pablo</option>
                                                <option value="La Noria">La Noria</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin sucursal --}}
                                {{-- Factura --}}
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
                                {{-- Fin factura --}}
                                {{-- Añadir Órden de compra --}}
                                @if ( $order->purchaseorder && ($role->name == "Administrador" || $department->name == "Compras" || $department->name == "Embarques") )
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Orden de Requisición</label>
                                        <div class="col-sm-2">
                                            <div class="form-group bmd-form-group is-filled">
                                                <input class="form-control" name="purchaseorder" id="purchaseorder" type="text" placeholder="Orden de Requisición" value="{{ $order->purchaseorder->number ? $order->purchaseorder->number : NULL }}" >
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
                                                <label for="document" class="custom-file-upload">
                                                    Factura
                                                </label>
                                                <input type="file" name="document" class="form-control-file" id="document" accept="image/*,.pdf" >
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <label for="requisition" class="custom-file-upload">
                                                    Requisición
                                                </label>
                                                <input type="file" name="requisition" class="form-control-file" id="requisition" accept="image/*,.pdf" >
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- fin de añadir órden de compra --}}
                                {{-- Clave del cliente --}}
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
                                {{-- Fin clave del cliente --}}
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
                                {{-- Opciones de visibilidad de acuerdo al estatus --}}
                                    {{-- Opcional en ruta --}}
                                    <?php 
                                    //var_dump($order->status_id);
                                    $rutaDisplay = ($order->status_id == 5 && !empty($order->shipments)) ? "": "style='display:none'"; 
                      
                              //var_dump($rutaDisplay);
                                    ?>
                                    <div class="row" id="route" {!! $rutaDisplay !!}>
                                    
                                    <div class="row col-sm-12">
                                            <label class="col-sm-2 col-form-label">
                                                <span class="material-icons">
                                                    warning
                                                </span>
                                                Material listo
                                            </label>
                                            <div class="col-sm-7 debdiv">
                           
                                            <section class='attachList' rel='ever' uploadto="{{ url('order/attachpost?catalog=shipments') }}" 
href="{{ url('order/attachlist?rel=ever&catalog=shipments&order_id='.$order->id) }}"></section>   
                                          
                                            </div>
                                    </div>
                                    
                                    
                                    <!-- 
                                        <div class="row col-sm-12">
                                            <label class="col-sm-2 col-form-label">
                                                <span class="material-icons">
                                                    warning
                                                </span>
                                                Material listo
                                            </label>
                                            <div class="col-sm-7">
                                                <div class="">
                                                    <label for="picture">Fotografía o PDF</label>
                                                    <input type="file" name="routeEvidence1" accept="capture=camera,image/*" class="form-control-file" id="picture"  >
                                                </div>
                                            </div>
                                          
                                            
                                            
                                        </div>
                                        
                                        
                                       @for ($i=2; $i <= 10 ; $i++)
                                       <div class="row col-sm-12 rutaev" rel="{{ $i }}">
                                            <label class="col-sm-2 col-form-label">
                                                <span class="material-icons">
                                                    warning
                                                </span>
                                                Material listo {{ $i }}
                                            </label>
                                            <div class="col-sm-7">
                                                <div class="">
                                                    <label for="picture">Fotografía o PDF {{ $i }}</label>
                                                    <input type="file" name="routeEvidence{{$i}}" class="form-control-file" id="picture"  accept="capture=camera,image/*">
                                                </div>
                                            </div>
                                        </div>
                                       @endfor
                                                                              

                                        
                                        <div class="row col-sm-12" id='rowAgregadorRouteEvidence' style='margin-top:10px'>
                                        <label  class="col-sm-2 col-form-label">
                                        Agregar Evidencia
                                        </label>
                                        <div class="col-sm-7">
                                        <label for='agregadorRouteEvidence'></label>
                                        <button id='agregadorRouteEvidence'> + Imagen </button>
                                        </div>
                                        </div>
                                        -->
                                        
                                    </div>
                                    {{-- Fin opcional en ruta --}}
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
                                                    <input type="file" name="cancelation" class="form-control-file" id="picture"   accept="capture=camera,image/*">
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
                                                    <input type="file" name="rebilling" class="form-control-file" id="picture"  accept="capture=camera,image/*">
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Fin opcional refacturación --}}
                                    {{-- Opcional al devolución --}}
                                    <?php 
                                    $deborId = !empty($order->debolution) ? $order->debolution->reason_id : 0 ;
                  
                                    $deboRowDisplay = (empty($deborId) && $order->status_id == 9 ) ? "" : "style='display:none'" ;            
                                                ?>
                                    <div class="row" id="devolReason" {!! $deboRowDisplay !!} >
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
                                                            <option {{ ($deborId == $reason->id) ? "selected='selected'" : "" }} value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                     
                                            @if (!empty($order->debolution) && $order->status_id == 9)
                                            <?php //var_dump($order->debolution); ?>
                                            <section class='attachList' rel='debo'  uploadto="{{ url('order/attachpost?catalog=evidence') }}" 
href="{{ url('order/attachlist?rel=debo&catalog=evidence&debolution_id='. $order->debolution->id) }}"></section>
                                            @else
                                            <section class='attachList adder' rel='debo' adder='{{ url("order/debolutioncreatefor?order_id=".$order->id) }}' uploadto="{{ url('order/attachpost?catalog=evidence') }}" 
href="{{ url('order/attachlist?rel=debo&catalog=evidence') }}"></section>
                                            @endif
                                            
                                            
                                            
                                            <!-- 
                                                <div class="">
                                                    <label for="picture">Fotografía o PDF del material</label>
                                                    <input type="file" name="debolution1" class="form-control-file" id="picture"  accept="capture=camera,image/*">
                                                </div>
                                                
                                                
                                                
                                                @for ($i=2; $i <= 5; $i++)
                                                 <div class="pmuploader" rel="{{ $i }}" group='devo'>
                                                    <label for="picture">Fotografía o PDF {{ $i }}</label>
                                                    <input type="file" name="debolution{{ $i }}" class="form-control-file" id="picture"  accept="capture=camera,image/*">
                                                </div>
                                                @endfor
                                                
                                                
                                                <div class="rowUploaderAdder" group='devo'>
                                                <br/>
                                                	<button class="UploaderAdder" group='devo'>Agregar Imagen</button>
                                                </div>
                                                -->
                                            </div>
                                            
                                            
                                       
                                            
                                            
                                        </div>
                                    </div>
                                    {{-- Fin opcional devolución --}}
                                    {{-- Opcional al Orden de fabricación --}}
                                    @if ( $order->manufacturingorder && $order->status_id == 3 )
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
                                                            <input type="file" name="manufacturingFile" class="form-control-file" id="picture"   accept="capture=camera,image/*" required="true" aria-required="true">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="manufacturingSection" comment="this exists to avoid a js error if not present"></div>
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
                                                        <input type="file" name="manufacturingFile" class="form-control-file" id="picture"   accept="capture=camera,image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Fin opcional orden de fabricación --}}
                                {{-- Fin Opciones de visibilidad de acuerdo al estatus --}}
                                    {{-- Ver Notas --}}
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
                                    {{-- Fin de ver notas --}}
                                    {{-- Añadir notas --}}
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
                                            <a class="btn btn-sm btn-primary" style="color: white" onclick="AgregarCampos()">
                                                <span class="material-icons">
                                                    add
                                                </span>
                                                Parcial
                                            </a>
                                        </div>
                                        <div id="campos" class="col-sm-10">
                                        </div>
                                    </div>
                                    {{-- Fin de añadir notas --}}
                                {{-- Parciales --}}
                                {{-- Ver parciales --}}
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
                                                        <tr rel='{{$partial->id}}'>
                                                            <td>
                                                                {{-- <a href="{{ route('partials.show', $partial->id) }}" class="btn btn-primary btn-link btn-sm"> --}}
                                                                    <p >{{ $partial->invoice }}</p>
                                                                {{-- </a> --}}
                                                            </td>
                                                            <td>
                                                                {{-- <a href="{{ route('partials.show', $partial->id) }}" class="btn btn-primary btn-link btn-sm"> --}}
                                                                    <input type="hidden" name="stPartID_{{ $count+1 }}" value="{{ $partial->id }}">
                                                                    <select name="stPartValue_{{ $count+1 }}" id="stPartValue_{{ $count+1 }}" class="form-control stPartSelector" >
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
                                                                
                                                                
                                                                <section class='attachList' rel='evp{{$partial->id}}' uploadto="{{ url('order/attachpost?catalog=pictures') }}" 
href="{{ url('order/attachlist?rel=evp'.$partial->id.'&catalog=pictures&partial_id='.$partial->id) }}"></section>
                                                                
                                                                <!--  
                                                                    <div class="form-check">
                                                                        <label for="">Evidencia de salida</label>
                                                                        <input type="hidden" name="partImgID_{{ $count+1 }}" value="{{ $partial->id }}">
                                                                        <input type="file" name="partImg_{{ $count+1 }}_0" class="form-control-file" accept="capture=camera,image/*">
                                                                    </div>
                                                                    
                                                                    
                                                                    
                                                                    @for ($i=2; $i <= 5; $i++)
                                                                     <div class="form-check pmuploader" rel="{{ $i }}" group="pes{{$count+1}}">
                                                                        <label for="picture">Evidencia de salida {{ $i }}</label>
                                                                        <input type="file" name="partImg_{{ $count+1 }}_{{ ($i-1) }}" class="form-control-file" id="picture"  accept="capture=camera,image/*">
                                                                    </div>
                                                                    @endfor                                                                    
                                                                    
                                                                    <div class="rowUploaderAdder" group='pes{{$count+1}}' style='text-align:left'>
                                                                    <br/>
                                                                    	<button class="UploaderAdder" group="pes{{$count+1}}">Agregar Imagen</button>
                                                                    </div>
                                                                 -->   

                                                                    
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
                                                                            <label for="">Evidencia de entrega parcial</label>
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
                                {{-- Script contador de parciales --}}
                                <div>
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
                                {{-- Fin del script de contador de parciales --}}

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
            element = document.getElementById("route");
            element.style.display='none';
            element = document.getElementById("manufacturingSection");
            element.style.display='block';
            element = document.getElementById("refactReason");
            element.style.display='none';
            element = document.getElementById("devolReason");
            element.style.display='none';
        }
        if( status_id == 5){
            element = document.getElementById("route");
            element.style.display='block';
            element = document.getElementById("cancelReason");
            element.style.display='none';
            element = document.getElementById("refactReason");
            element.style.display='none';
            element = document.getElementById("devolReason");
            element.style.display='none';
            element = document.getElementById("manufacturingSection");
            element.style.display='none';


            AttachList("ever");
            
        }
        if( status_id == 5 && credit == true ){
            alert('Para pedidos por cobrar o pedidos programados es necesario ligar a una factura');
            document.getElementById("invoice_number").placeholder = "Para pedidos a crédito se requiere un número de factura!";
            document.getElementById("invoice_number").requiered = true;
            document.getElementById("invoice_number").focus();
        }
        if( status_id == 6 && p != e ){
            alert('No puedes marcar de entregado el pedido hasta entregar cada parcial');
        }
        if( status_id == 7 ){ // Cancelado
            element = document.getElementById("route");
            element.style.display='none';
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
            element = document.getElementById("route");
            element.style.display='none';
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
            element = document.getElementById("route");
            element.style.display='none';
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
            element = document.getElementById("route");
            element.style.display='none';
            element = document.getElementById("cancelReason");
            element.style.display='none';
            element = document.getElementById("refactReason");
            element.style.display='none';
            element = document.getElementById("devolReason");
            element.style.display='none';
            element = document.getElementById("manufacturingSection");
            element.style.display='none';
        }
        /* Para obtener el texto */
        // var combo = document.getElementById("status_id");
        // var selected = combo.options[combo.selectedIndex].text;
        // console.log(selected);

    }



    /*
    function RutaEvidencias(){
		$(".rutaev").hide();
		
        }
    function AddRutaEvidencia(){
		var lastRel = 1;
		var r=2;
			$(".rutaev").each(function(){
				if($(this).is(":visible")){lastRel=r;}
				r++;
			});
		
			var nextRel = lastRel+1;
			if(nextRel > 10 ){HideRutaEvidenciaAdder();return;}

			$(".rutaev[rel='"+nextRel+"']").slideDown();
			if(nextRel > 9 ){HideRutaEvidenciaAdder();}
        }

   function HideRutaEvidenciaAdder(){
       $("#rowAgregadorRouteEvidence").hide();
       }

	$("#agregadorRouteEvidence").click(function(e){
		e.preventDefault();
		AddRutaEvidencia();
		});
    RutaEvidencias();



    function MultiUploads(){
		$(".pmuploader").hide();
		
        }
    function AddUploader(group){
		var lastRel = 1;
		var r=2;
			$(".pmuploader[group='"+ group +"']").each(function(){
				if($(this).is(":visible")){lastRel=r;}
				r++;
			});
		
			var nextRel = lastRel+1;
			if(nextRel > 5 ){HideUploaderAdder( group ); return;}
//console.log(nextRel);
			$(".pmuploader[group='"+ group +"'][rel='"+nextRel+"']").slideDown();
			if(nextRel > 4 ){HideUploaderAdder( group );}
        }

   function HideUploaderAdder(group){
       $(".rowUploaderAdder[group='"+ group +"']").hide();
       }

	$(".UploaderAdder").click(function(e){
		e.preventDefault();
		var group = $(this).attr("group");
		AddUploader(group);
		});
	
	MultiUploads();
	*/


    
	function actualizar(ob){
		//PLACEHOLDER
		console.log(ob);
		var val = $(ob).val();
		if(val==5){
			FormaAgregarParcial(5);
			}
		
		}

	
    var nextinput = {{ $order->partials->count() }};
    function AgregarCampos(){
        nextinput++;
        campo = ``;
        console.log(nextinput);
        if(nextinput < 6){
            campo = `
            <div class="col-sm-8">
                <div class="form-group bmd-form-group is-filled">
                    <input class="form-control " rel="folioInput" name="folio`+nextinput+`" id="input-address" type="text" placeholder="Folio `+nextinput+`" value="" required="true" aria-required="true">
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


   function FormaAgregarParcial(statusId){
		var h = "<div id='FormaNuevoParcial'>";
		//h+="<input type='text' name='folio' size='48' maxlength='110' />";
		//h+= "<div>"+statusId+"</div>";
		h+="<input type='button' class='btn' id='AgregadorDeParcial' onclick='AgregarParcial("+statusId+")' value='Agregar Imagenes' />";
   		//h+="<p><button onclick='MiModalCerrar()'>Cancelar</button></p>";
   		h+="</div>";
		//MiModal(h);
		$("#campos").append(h);
       }


    function AgregarParcial(statusId){
        var folio = $("[rel='folioInput']").val();
        if(typeof(folio)=="" || folio==""){
			alert("Por favor indica un folio");
			return;
            }

     
	$.ajax({
		url:"{{ url('order/partialcreatefor') }}",
		data:{"order_id":"{{$order->id}}","status_id":statusId,"folio":folio},
		dataType:"json",
		success:function(json){
			if(json.status==1){
				//AgregarParcialRow(json.value);
				AgregarParcialAttachlist(json.value);
				}
			else{
				alert(json.errors);
				}
			}
		});

        
    }

    function AgregarParcialAttachlist(parcialId){
        var ht = "<section class='attachList' rel='evp"+parcialId+"' uploadto='{{ url('order/attachpost?catalog=pictures') }}'" ;
        ht += "href='{{ url('order/attachlist') }}?rel=evp"+parcialId+"&catalog=pictures&partial_id="+parcialId+"'></section>";
        $("#campos").append(ht);
        AttachList("evp"+parcialId);
        $("[rel='folioInput']").prop("name","folioInput");
        $("#AgregadorDeParcial").remove();
        }

    /*    
    function AgregarParcialRow(parcialId){
        var newTr = "<tr jsCreated='true'><td  class='text-center'>";
        newTr +="<input type='text' class='form-control' name='folioNew' size='48' maxlength='190' />";
        newTr += "<section class='attachList' rel='evp"+parcialId+"' uploadto='{{ url('order/attachpost?catalog=pictures') }}'" ;
        newTr += "href='{{ url('order/attachlist?rel=evp"+parcialId+"&catalog=pictures&partial_id="+parcialId+"'></section>";
        newTr += "</td></tr>";

        var toNum = $("table#orders").length;
        	if(toNum<1){CrearTableParciales();}
        	
    	$("table#orders body").append( newTr );
		$("[name='folioNew']").focus();
        }

        function CrearTableParciales(){
           var h =' <div class="row"><label class="col-sm-2 col-form-label">Parciales </label>';
            h+='<div class="col-sm-10"><table class="table data-table" id="orders">';
            h+='<thead><tr><th >Folio</th><th >Estatus</th><th class="text-center">Acciones</th>';
            h+='</tr></thead><tbody></tbody></table></div></div>';

           $(".card_body").append(h);
            }
    */


<?php $t=10; ?>
$(document).ready(function(){

    @foreach ($order->partials as $part)
    	@if ( $part->status->name == 'En ruta' )
    	AttachList("evp{{$part->id}}");
		@endif
    @endforeach


    var deboHasAdder = $(".attachList[rel='debo']").hasClass("adder");
	    if(!deboHasAdder){
	        AttachList("debo");
		    }
	var routeVisible = $("#route").is(":visible");
		if(routeVisible){
			AttachList("ever");
			}
    
    $("[name='debolution_reason_id']").change(function(){        

        if(!deboHasAdder){return;}
        
    		var href = $(".attachList[rel='debo']").attr("adder");
    		var val = $(this).val();
    		href += "&reason_id=" + val ;
    		
            $.ajax({
                url:href,
                type:"get",
                dataType:"json",
                success:function(json){
                    if(json.status==1){
                        
                        var href = $(".attachList[rel='debo']").attr("href");
                        $(".attachList[rel='debo']").attr("href", href + "&debolution_id=" + json.value);
                        AttachList("debo");
                    }
                    
                }
            });
    });


    $(".stPartSelector").change(function(){
        var val = $(this).val();
        if(val==5){
            let cnfText ="Cambiar estatus a En Ruta y agregar imágenes de evidencia de shipment.";
            if(!confirm(cnfText)){
                return;
            }
        var partid = $(this).closest("tr").attr("rel");    
        var ht = "<section class='attachList' rel='evp"+partid+"' uploadto='{{ url('order/attachpost?catalog=pictures') }}'" ;
        ht += "href='{{ url('order/attachlist') }}?rel=evp"+partid+"&catalog=pictures&partial_id="+partid+"'></section>";

        var nextTd = $(this).closest("td").next("td");
        //console.log(nextTd);
            if(nextTd.length < 1){
                $(this).closest("tr").append("<td></td>");    
            }
        $(this).closest("td").next("td").html(ht);
        AttachList("evp"+partid);    
        }
    });
    
    console.log("attachlist!");
});
    
</script>

<script type='text/javascript' src='{{ url("/")."/js/attachlist.js" }}'></script>
<link rel="stylesheet" href="{{ url('/') }}/css/attachlist.css" >

@endpush

