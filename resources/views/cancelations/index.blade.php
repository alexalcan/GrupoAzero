@extends('layouts.app', ['activePage' => 'departments', 'titlePage' => __('Departamentos')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">

        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">Departamentos</h4>
                        <p class="card-category"> Departamentos de la empresa</p>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                        <a href="{{ route('departments.create') }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                add_circle_outline
                            </span>
                            Nuevo departamento
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
              <div class="table-responsive ">
                <table class="table data-table" id="departments">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th width="50px">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
                                    <a href="{{ route('departments.edit', $role->id) }}" class="btn btn-primary btn-link btn-sm">
                                        <span class="material-icons">
                                            edit
                                        </span>
                                    </a>
                                </td>
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
@endsection

@push('js')

@endpush
