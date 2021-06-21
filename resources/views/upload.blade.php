@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Evidencia de Entrega')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('picture.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Subir evidencia de entregado</h4>
                            <p class="card-category">Pedido {{ $order->invoice }}</p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <label class="col-sm-2 col-form-label">Foto</label>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label for="picture">Click para subir o tomar foto</label>
                                        <input type="file" name="picture" class="form-control-file" id="picture" accept="image/*" capture="camera" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label">Nota (opcional)</label>
                                <div class="col-sm-7">
                                    <div class="form-group bmd-form-group is-filled">
                                        <textarea class="form-control" name="note" id="exampleFormControlTextarea1" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
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
