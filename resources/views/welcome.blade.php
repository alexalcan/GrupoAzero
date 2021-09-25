@extends('layouts.welcome', ['class' => 'off-canvas-sidebar', 'activePage' => 'home', 'title' => __('Grupo Azero')])

@section('content')
<div class="container" style="height: auto;">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-8 col-sm-8 col-xs-10">
            <form class="navbar-form" method="POST" action="{{ route('homesearch') }}">
                @csrf
                @method('get')
                <div class="row">
                    <div class="col-12">
                        <h3>Introduce el número de factura...</h3>
                        {{-- <span class="bmd-form-group"> --}}
                            <div class="input-group no-border">
                                <input type="text" name="invoice" value="" class="form-control" placeholder="# Factura..." required>
                            </div>
                        {{-- </span> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>Introduce el número de cliente...</h3>
                        {{-- <span class="bmd-form-group"> --}}
                            <div class="input-group no-border">
                                <input type="text" name="client" value="" class="form-control" placeholder="# Factura..." required>
                            </div>
                        {{-- </span> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="material-icons">search</i>
                            Buscar
                            {{-- <div class="ripple-container"></div> --}}
                        </button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-12">
                    <div class="input-group no-border">
                        @if ( !(isset($order)) )
                            <p>{{ (isset($message)) ? $message : '' }}</p>
                        @else
                            <p>{{ $message }}</p>
                            <table class="table">
                                <thead>
                                    <th>Pedido</th>
                                    <th>Estatus actual</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ $order->invoice }}
                                        </td>
                                        <td>
                                            {{ $order->status->name }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Entregas --}}
            @if ( isset($order->pictures) )
                <div class="row">
                    @foreach ($order->pictures as $picture)
                        <div class="col-sm-12">
                            <div class="card" style="width: 100%;  height: 600px">
                                <embed class="card-img-top" src="{{ asset('storage') }}/{{ $picture->picture }}" style="width: 100%; height: 100%;">
                                <div class="card-body">
                                    <p class="card-text">Foto subida el {{ $picture->created_at->isoFormat('MMM Do YY') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            {{-- Fin de entregas --}}
            {{-- Entregas con parciales --}}
            @if ( isset($order->partials) && $order->partials->count() > 0 )
                <div class="row">
                    {{-- <h2>Entregas Parciales</h2><br> --}}
                    @foreach ( $order->partials as $partial )
                        @foreach ($partial->pictures as $picture)
                            <div class="card" style="width: 100%;  height: 600px">
                                <embed class="card-img-top" src="{{ asset('storage') }}/{{ $picture->picture }}" style="width: 100%; height: 100%;">
                                <div class="card-body">
                                <p class="card-text">Parcial: {{ $picture->partial->invoice }} - Entregado: {{ $picture->created_at->toDateTimeString() }} por {{ $picture->user->name }}</p>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="col-sm-12">
                            <div class="card" style="width: 100%;">
                                <img class="card-img-top" src="{{ asset('storage') }}/{{ $picture->picture }}" alt="Card image cap">
                                <div class="card-body">
                                    <p class="card-text">Foto subida el {{ $picture->created_at->isoFormat('MMM Do YY') }}</p>
                                </div>
                            </div>
                        </div> --}}
                    @endforeach
                </div>
            @endif
            {{-- Fin de entregas --}}
            {{-- Evidencias de fabricaciones --}}
            @if ( isset($order->shipments) )
                <div class="row">
                    @foreach ($order->shipments as $shipment)
                        <div class="col-sm-12">
                            <div class="card" style="width: 100%;  height: 600px">
                                @if ( pathinfo($shipment->file, PATHINFO_EXTENSION) == "png" )
                                    <img src="{{ asset('storage') }}/{{ $shipment->file }}" alt="" style="width: 100%">
                                    <div class="card-body">
                                        <p class="card-text">Foto subida el {{ $shipment->created_at->isoFormat('MMM Do YY') }}</p>
                                    </div>
                                @else
                                    <embed src="{{ asset('storage') }}/{{ $shipment->file }}" alt="" style="width: 100%; height: 600px;">
                                    <div class="card-body">
                                        <p class="card-text">Foto subida el {{ $shipment->created_at->isoFormat('MMM Do YY') }}</p>
                                    </div>
                                @endif
                                {{-- <embed class="card-img-top" src="{{ asset('storage') }}/{{ $shipment->file }}" style="width: 100%; height: 100%;"> --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            {{-- Fin de evidencias de fabricaciones --}}

            {{-- Cancelaciones --}}
                {{-- Evidencias --}}
                @if ( isset($order->cancelation) )
                    @if ( $order->status_id == 7 )
                        <div class="row">
                            @foreach ($order->cancelation->evidences as $evidence)
                                <div class="col-sm-12">
                                    <div class="card" style="width: 100%; height: 600px">
                                        @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $evidence->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 600px;">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $evidence->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @endif
                                        {{-- <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 100%;">
                                        <div class="card-body">
                                            <p class="card-text">Foto subida el {{ $evidence->cancelation->created_at->isoFormat('MMM Do YY') }}</p>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
                {{-- Fin de evidencias --}}
                {{-- Reembolsos --}}
                @if ( isset($order->cancelation) )
                    @if ( $order->status_id == 7 )
                        <div class="row">
                            @foreach ($order->cancelation->repayments as $repayment)
                                <div class="col-sm-12">
                                    <div class="card" style="width: 100%; height: 600px">
                                        @if ( pathinfo($repayment->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $repayment->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 600px;">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $repayment->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @endif
                                        {{-- <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 100%;">
                                        <div class="card-body">
                                            <p class="card-text">Foto subida el {{ $repayment->cancelation->created_at->isoFormat('MMM Do YY') }}</p>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
                {{-- Fin de reembolsos --}}
            {{-- Fin de cancelaciones --}}
            {{-- Refacturaciones --}}
                {{-- Evidencias --}}
                @if ( isset($order->rebilling) )
                    @if ( $order->status_id == 8 )
                        <div class="row">
                            @foreach ($order->rebilling->evidences as $evidence)
                                <div class="col-sm-12">
                                    <div class="card" style="width: 100%; height: 600px">
                                        @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $evidence->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 600px;">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $evidence->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @endif
                                        {{-- <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 100%;">
                                        <div class="card-body">
                                            <p class="card-text">Foto subida el {{ $evidence->rebilling->created_at->isoFormat('MMM Do YY') }}</p>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
                {{-- Fin de evidencias --}}
                {{-- Reembolsos --}}
                @if ( isset($order->rebilling) )
                    @if ( $order->status_id == 8 )
                        <div class="row">
                            @foreach ($order->rebilling->repayments as $repayment)
                                <div class="col-sm-12">
                                    <div class="card" style="width: 100%; height: 600px">
                                        @if ( pathinfo($repayment->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $repayment->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 600px;">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $repayment->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @endif
                                        {{-- <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 100%;">
                                        <div class="card-body">
                                            <p class="card-text">Foto subida el {{ $repayment->rebilling->created_at->isoFormat('MMM Do YY') }}</p>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
                {{-- Fin de reembolsos --}}
            {{-- Fin de refacturaciones --}}
            {{-- Devoluciones --}}
                {{-- Evidencias --}}
                @if ( isset($order->debolution) )
                    @if ( $order->status_id == 9 )
                        <div class="row">
                            @foreach ($order->debolution->evidences as $evidence)
                                <div class="col-sm-12">
                                    <div class="card" style="width: 100%; height: 600px">
                                        @if ( pathinfo($evidence->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $evidence->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 600px;">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $evidence->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @endif
                                        {{-- <embed src="{{ asset('storage') }}/{{ $evidence->file }}" alt="" style="width: 100%; height: 100%;">
                                        <div class="card-body">
                                            <p class="card-text">Foto subida el {{ $evidence->debolution->created_at->isoFormat('MMM Do YY') }}</p>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
                {{-- Fin de evidencias --}}
                {{-- Reembolsos --}}
                @if ( isset($order->debolution) )
                    @if ( $order->status_id == 9 )
                        <div class="row">
                            @foreach ($order->debolution->repayments as $repayment)
                                <div class="col-sm-12">
                                    <div class="card" style="width: 100%; height: 600px">
                                        @if ( pathinfo($repayment->file, PATHINFO_EXTENSION) == "png" )
                                            <img src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $repayment->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @else
                                            <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 600px;">
                                            <div class="card-body">
                                                <p class="card-text">Foto subida el {{ $repayment->created_at->isoFormat('MMM Do YY') }}</p>
                                            </div>
                                        @endif
                                        {{-- <embed src="{{ asset('storage') }}/{{ $repayment->file }}" alt="" style="width: 100%; height: 100%;">
                                        <div class="card-body">
                                            <p class="card-text">Foto subida el {{ $repayment->debolution->created_at->isoFormat('MMM Do YY') }}</p>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
                {{-- Fin de reembolsos --}}
            {{-- Fin de devoluciones --}}
        </div>
    </div>
</div>
@endsection
