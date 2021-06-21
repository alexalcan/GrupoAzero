@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('Usuarios')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Editar perfil</h4>
                                        <p class="card-category">Información básica del usuario</p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                arrow_back
                                            </span>

                                            Regresar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Nombre</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="name" id="input-name" type="text" placeholder="Name" value="{{ $user->name }}" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="email" id="input-email" type="email" placeholder="Email" value="{{ $user->email }}" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Rol</label>
                                    <div class="col-sm-7">
                                        <select name="role" id="inputState" class="form-control">
                                            <option value="{{ $user->role->id }}" selected><b>{{ $user->role->name }}</b></option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Departamento</label>
                                    <div class="col-sm-7">
                                        <select name="department" id="inputState" class="form-control">
                                            <option value="{{ $user->department->id }}" selected><b>{{ $user->department->name }}</b></option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
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
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('put')
                    <input type="hidden" name="type" value="changepassword">
                    <div class="card ">
                        <div class="card-header card-header-primary">
                        <h4 class="card-title">Password Reset</h4>
                        <p class="card-category">En caso de olvido, se puede restablecer un password</p>
                        </div>
                        <div class="card-body ">
                        @if (session('status_password'))
                            <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="material-icons">close</i>
                                </button>
                                <span>{{ session('status_password') }}</span>
                                </div>
                            </div>
                            </div>
                        @endif
                        {{-- <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-current-password">{{ __('Current Password') }}</label>
                            <div class="col-sm-7">
                            <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                <input class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" input type="password" name="old_password" id="input-current-password" placeholder="{{ __('Current Password') }}" value="" required />
                                @if ($errors->has('old_password'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                            </div>
                        </div> --}}
                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-password">Nuevo password</label>
                            <div class="col-sm-7">
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="input-password" type="text" placeholder="Nuevo password" value="" required />
                                @if ($errors->has('password'))
                                <span id="password-error" class="error text-danger" for="input-password">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                        <button type="submit" class="btn btn-primary">Cambiar Password</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('users.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Zona peligrosa</h4>
                            <p class="card-category">Borrar al usuario</p>
                        </div>
                        <div class="card-body text-center">
                            <h6>Al eliminar el usuario, no podrán ser recuperados sus datos</h6>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-danger">Entiendo, borrar usuario</button>
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

