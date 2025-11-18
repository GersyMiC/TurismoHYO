@extends('layouts.base')

@section('titulo', 'Ingresar')

@section('contenido')
  <h2 class="h4 mb-3">Ingresar</h2>

  <form method="post" action="{{ route('auth.login') }}" class="card p-3">
    @csrf

    <div class="mb-2">
      <label class="form-label">Correo</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="mb-2">
      <label class="form-label">Contraseña</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="m-0">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Ingresar</button>
      <a href="{{ route('auth.register') }}" class="btn btn-outline-secondary">Crear cuenta</a>
    </div>
  </form>
@endsection
