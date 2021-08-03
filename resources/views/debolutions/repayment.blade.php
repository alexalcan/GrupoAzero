@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Nota de reembolso o Crédito de devolución')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('debolutions.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="type" value="repayment">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Subir nota de reembolso o crédito</h4>
                            <p class="card-category">Pedido {{ $order->invoice }}</p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <label class="col-sm-2 col-form-label">Archivo</label>
                                <div class="col-sm-7">
                                    <div class="">
                                        <label for="picture">Adjuntar archivo de imágen o pdf</label>
                                        <input type="file" name="file" class="form-control-file" id="picture" accept="image/*,.pdf" required>
                                    </div>
                                </div>
                            </div>
                            @if ( isset($order->debolution) )
                                <input type="hidden" name="olddebolution" value="true" class="form-control">
                                <input type="hidden" name="debolutionId" value="{{ $order->debolution->id }}" class="form-control">
                            @else
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Razón</label>
                                    <div class="col-sm-7">
                                        <select name="reason_id" id="reason_id" class="form-control" required>
                                            <option value="1" selected><b>Selecciona una razón...</b></option>
                                            @foreach ($reasons as $reason)
                                                <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                            @endforeach
                                        </select>
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
