@extends('layouts.base')

@section('titulo', 'Crear cuenta')

@section('contenido')
  <h2 class="h4 mb-3">Crear cuenta</h2>

  <form method="post" action="{{ route('auth.register') }}" class="card p-3">
    @csrf

    <div class="mb-2">
      <label class="form-label">Nombre completo</label>
      <input type="text" name="nombre_completo" class="form-control" value="{{ old('nombre_completo') }}" required>
    </div>

    <div class="mb-2">
      <label class="form-label">Correo</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="mb-2">
      <label class="form-label">Teléfono (opcional)</label>
      <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
    </div>

    <div class="mb-2">
      <label class="form-label">Contraseña</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Confirmar contraseña</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    @if(session()->has('uid') && $authUser->roles->contains('admin'))
        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select">
                <option value="cliente" selected>Cliente</option>
                <option value="agente">Agente</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
    @endif


    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="m-0">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Registrarme</button>
      <a href="{{ route('auth.login') }}" class="btn btn-outline-secondary">Ya tengo cuenta</a>
    </div>
  </form>
@endsection
