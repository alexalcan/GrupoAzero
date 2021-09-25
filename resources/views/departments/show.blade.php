@extends('layouts.app', ['activePage' => 'roles', 'titlePage' => __('Roles')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">

        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">{{ $role->name }}</h4>
                        <p class="card-category"> {{ $role->id }}</p>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                arrow_back
                            </span>

                            Regresar
                        </a>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                person_add
                            </span>
                            Editar rol
                        </a>
                    </div>
                </div>

            </div>
            <div class="card-body ">
                <div class="row">
                    <label class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="name" id="input-name" type="text" placeholder="Name" value="{{ $role->name }}" disabled="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="email" id="input-email" type="email" placeholder="Email" value="{{ $role->email }}" disabled="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Rol</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="email" id="input-email" type="text" placeholder="Email" value="{{ $role->role->name }}" disabled="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Departamento</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="email" id="input-email" type="text" placeholder="Email" value="{{ $role->department->name }}" disabled="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Creado el</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="email" id="input-email" type="text" placeholder="Email" value="{{ $role->created_at->toDateTimeString() }}" disabled="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Última actualización de datos</label>
                    <div class="col-sm-7">
                        <div class="form-group bmd-form-group is-filled">
                            <input class="form-control" name="email" id="input-email" type="text" placeholder="Email" value="{{ $role->updated_at->toDateTimeString() }}" disabled="">
                        </div>
                    </div>
                </div>

            </div>
          </div>
      </div>
    </div>
  </div>
@endsection

@push('js')

@endpush

