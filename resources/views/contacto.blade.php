@extends('layouts.base')

@section('titulo', 'Contacto')

@section('contenido')
    <div class="container mt-5">
        <h2>Contacto</h2>
        <p>Si tienes alguna consulta, no dudes en enviarnos un mensaje. Estaremos encantados de ayudarte.</p>

        <!-- Formulario de contacto -->
        <form action="{{ route('contacto.enviar') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje</label>
                <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
@endsection



