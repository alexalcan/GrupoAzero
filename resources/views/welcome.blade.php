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
                                    <th>Factura</th>
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
            @if ( isset($order->cancelation) )
                @if ( $order->status_id == 7 )
                    <div class="row">
                        @foreach ($order->cancelation->files as $file)
                            <div class="col-sm-12">
                                <div class="card" style="width: 100%;">
                                    <img class="card-img-top" src="{{ asset('storage') }}/{{ $file->file }}" alt="Card image cap">
                                    <div class="card-body">
                                        <p class="card-text">Foto subida el {{ $file->cancelation->created_at->isoFormat('MMM Do YY') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
