@extends('layouts.app', ['activePage' => 'archived', 'titlePage' => __('Pedidos archivados')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">

            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                            <h4 class="card-title ">Pedidos archivados</h4>
                            <p class="card-category"> {{ auth()->user()->name }} / {{ $role->name }} / {{ $department->name }} </p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="navbar-form" method="GET" action="{{ route('archived.index') }}">
                        @csrf
                        @method('get')
                        <div class="row">

                            <div class="col-2">
                                <div class="form-group no-border">
                                    <!-- <label class="label-control">Buscar por fecha</label> -->
                                    <input type="text" name="fecha" class="form-control datetimepicker" placeholder="Buscar por fecha"/>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input name="ranDate" id="ranDate" value="1" onchange="javascript:addInvoice()" class="form-check-input" type="checkbox" >
                                        Rango de fecha
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-2" id="fechaDos" style="display: none;">
                                <div class="form-group no-border">
                                    <!-- <label class="label-control">Buscar por fecha</label> -->
                                    <input type="text" name="fechaDos" class="form-control datetimepicker" placeholder="Fecha final..."/>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group no-border">

                                    <input type="text" name="busqueda" value="" class="form-control" placeholder="Buscar por folio, factura, cliente, sucursal..." style="">
                                        <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                            <i class="material-icons">search</i>
                                        </button>
                                </div>
                            </div>


                        </div>
                        @if ($fecha || $texto)
                            <div class="row">
                                <div class="col-12">
                                    <h5>Resultado de busqueda:
                                        @if( $fecha )
                                            fecha: {{ $fecha ?? '' }}
                                        @endif
                                        @if( $fechaDos )
                                            al: {{ $fechaDos ?? '' }}
                                        @endif
                                        @if( $texto )
                                            Criterio: {{ $texto ?? '' }}
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        @endif
                    </form>

                    <div class="table-responsive ">
                    <table class="table data-table" id="orders">
                        <thead>
                            <tr>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Sucursal</th>
                                <th class="text-center">Folio</th>
                                <th class="text-center col-hd">Factura</th>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Acciones</th>
                                {{-- @if ( $role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Ventas" || $department->name == "Fabricación")
                                    <th width="50px">&nbsp;</th>
                                @endif --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('archived.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            {{ $order->created_at->format('d-m-Y') }} <br>
                                            {{ $order->created_at->format('h:i') }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('archived.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ $order->office }}</p>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('archived.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ $order->invoice }}</p>
                                        </a>
                                    </td>
                                    <td class="col-hd">
                                        <a href="{{ route('archived.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ $order->invoice_number }}</p>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('archived.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ $order->client }}</p>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('archived.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ $order->status->name }}</p>
                                        </a>
                                    </td>
                                    {{-- Sección de alertas --}}
                                    <td class="text-right">

                                        {{-- Ver y editar --}}
                                        <form action="{{ route('archived.destroy', $order->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm" type="submit">
                                                <span class="material-icons">
                                                    unarchive
                                                </span>
                                                Desarchivar
                                            </button>
                                        </form>
                                        {{-- Fin de ver y editar --}}
                                    </td>
                                    {{-- Fin de sección de alertas --}}
                                    {{-- @if ( $role->name == "Administrador" || $department->name == "Ventas" || $department->name == "Embarques" || $department->name == "Fabricación")
                                        <td>
                                            <a href="{{ route('archived.edit', $order->id) }}" class="btn btn-primary btn-link btn-sm">
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
                    {{ $orders->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

<!-- <script>
    $(document).ready(function() {
        $('#orders').DataTable({
            // "serverSide": true,
            // "ajax": "{{ url('api/orders') }}",
            // "columns": [
            //     {data: 'id'},
            //     {data: 'name'},
            //     {data: 'email'},
            //     {data: 'btn'},
            // ],
            ordering: true,
            order: [[1, "desc" ]],

            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    });
</script> -->

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

<script type="text/javascript">
    function addInvoice() {
        element = document.getElementById("fechaDos");
        ranDate = document.getElementById("ranDate");
        if (ranDate.checked) {
            element.style.display='block';
        }
        else {
            element.style.display='none';
        }
    }
</script>

{{-- <script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('orders.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
  </script> --}}

    <script type="text/javascript">
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    </script>
@endpush
