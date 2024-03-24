@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')


<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/index.css') }}" />

<main class="content">
    <section class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedido {{ $pedido->invoice }}</h4>
         </div>
    </div>

<p>&nbsp;</p>

    <form action="{{ url('pedidos2/guardar/'.$pedido->id) }}">
    <fieldset>
            <p>Iniciar con</p>
            <div>
                <button>Factura</button>
                <button>Cotización</button>
                <button>Requerimiento Interno</button>
            </div>
        </fieldset>
        <fieldset>
            <div>
            <label>Folio de Factura</label> <input type="text" name="invoice" />
            </div>
            <div>
            <label>Archivo</label> <input type="text" name="invoice" />
            </div>
        </fieldset>
    </form>


    @endsection

@push('js')

@endpush