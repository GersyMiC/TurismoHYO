@extends('layouts.base')

@section('titulo', 'Turismo HYO | Viajes personalizados en Perú')

@section('contenido')
  {{-- HERO: presentación sin catálogo --}}
  <section class="position-relative overflow-hidden rounded-3 p-5 text-white"
           style="background: linear-gradient(135deg,#0d6efd 0%,#6610f2 100%);">
    <div class="row align-items-center">
      <div class="col-12 col-lg-7">
        <h2 class="display-6 fw-bold mb-2">Bienvenido a Turismo HYO</h2>
        <p class="lead mb-4">
          Diseña tu viaje ideal por el Perú: fechas, actividades, alojamiento y transporte
          totalmente a tu medida. Somos operadores locales, con soporte real y pago seguro.
        </p>
        <div class="d-flex flex-wrap gap-2">
          {{-- Enlazaremos estas rutas más adelante --}}
          <a href="#" class="btn btn-light btn-lg">Personalizar mi viaje</a>
          <a href="#" class="btn btn-outline-light btn-lg">Conocer más</a>
        </div>
      </div>
      <div class="col-12 col-lg-5 mt-4 mt-lg-0">
        <div class="bg-white text-dark rounded-3 p-4 shadow-sm">
          <h5 class="mb-3">¿Listo para empezar?</h5>
          <ul class="m-0 ps-3 small">
            <li>Elige tu destino y fechas</li>
            <li>Selecciona actividades y hospedaje</li>
            <li>Recibe un precio en tiempo real</li>
            <li>Reserva y paga de forma segura</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  {{-- VALOR: por qué elegirnos --}}
  <section class="mt-4">
    <div class="row g-3">
      <div class="col-12 col-md-6 col-xl-3">
        <div class="border rounded-3 p-3 h-100">
          <h6 class="mb-1">Paquetes 100% a medida</h6>
          <p class="text-muted small mb-0">Arma tu itinerario con actividades opcionales y upgrades.</p>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-3">
        <div class="border rounded-3 p-3 h-100">
          <h6 class="mb-1">Operador local</h6>
          <p class="text-muted small mb-0">Equipo en Huancayo y socios verificados en todo el país.</p>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-3">
        <div class="border rounded-3 p-3 h-100">
          <h6 class="mb-1">Pago seguro</h6>
          <p class="text-muted small mb-0">Pasarelas confiables y confirmación inmediata de reserva.</p>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-3">
        <div class="border rounded-3 p-3 h-100">
          <h6 class="mb-1">Soporte 24/7</h6>
          <p class="text-muted small mb-0">Acompañamiento antes, durante y después del viaje.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- CÓMO FUNCIONA: pasos simples --}}
  <section class="mt-4">
    <div class="p-4 border rounded-3">
      <h3 class="h5 mb-3">¿Cómo funciona?</h3>
      <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
          <div class="p-3 bg-light rounded-3 h-100">
            <div class="fw-bold">1. Inspírate</div>
            <div class="small text-muted">Explora ideas y define tu presupuesto.</div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
          <div class="p-3 bg-light rounded-3 h-100">
            <div class="fw-bold">2. Personaliza</div>
            <div class="small text-muted">Elige actividades, hotel y transporte a tu gusto.</div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
          <div class="p-3 bg-light rounded-3 h-100">
            <div class="fw-bold">3. Reserva</div>
            <div class="small text-muted">Confirma fechas y realiza el pago con seguridad.</div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
          <div class="p-3 bg-light rounded-3 h-100">
            <div class="fw-bold">4. ¡Viaja!</div>
            <div class="small text-muted">Recibe tus vouchers y disfruta del itinerario.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- TESTIMONIOS / CONFIANZA --}}
  <section class="mt-4">
    <h3 class="h5 mb-3">Lo que dicen nuestros viajeros</h3>
    <div class="row g-3">
      <div class="col-12 col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <p class="mb-2">“Servicio excelente y atención rápida. Personalicé mi viaje a la Selva Central sin complicaciones.”</p>
            <span class="small text-muted">— Ana R.</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <p class="mb-2">“Buen precio y soporte 24/7. Recomendado si quieres flexibilidad en tu itinerario.”</p>
            <span class="small text-muted">— Luis M.</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <p class="mb-2">“Proceso claro de pago y confirmación inmediata. Viaje 10/10.”</p>
            <span class="small text-muted">— Sofía P.</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA FINAL --}}
  <section class="mt-4">
    <div class="p-4 rounded-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between"
         style="background:#f0f5ff;border:1px solid #d6e4ff;">
      <div class="mb-3 mb-md-0">
        <h4 class="h5 mb-1">¿Empezamos a planear tu viaje?</h4>
        <p class="m-0 text-muted small">Personaliza tu experiencia en minutos. Sin pagos hasta confirmar.</p>
      </div>
      <a href="#" class="btn btn-primary btn-lg">Comenzar ahora</a>
    </div>
  </section>
@endsection

@section('aside')
  {{-- Aside opcional: contacto rápido --}}
  <div class="p-3 border rounded">
    <h6 class="mb-2">Contacto rápido</h6>
    <p class="small mb-1">WhatsApp: +51 999 999 999</p>
    <p class="small m-0">Correo: info@turismohyo.pe</p>
  </div>
  <div class="p-3 border rounded mt-3">
    <h6 class="mb-2">Horario de atención</h6>
    <p class="small m-0">Lun–Sáb: 9:00–19:00</p>
  </div>
@endsection
