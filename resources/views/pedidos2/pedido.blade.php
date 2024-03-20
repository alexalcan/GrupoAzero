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

    <form>
    <div><label>Folio</label><input type="text" name="folio" /></div>

    </form>

    </section>
</main>

{{ $id }}

<?php var_dump($pedido); ?>

@endsection

@push('js')

@endpush