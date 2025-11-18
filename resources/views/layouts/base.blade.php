<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('titulo', 'Turismo HYO')</title>

  {{-- CDN de Bootstrap para avanzar rápido sin compilar assets --}}
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">
  <style>
    /* Ajustes simples para ver la estructura */
    header, nav, main, aside, footer { padding: 12px; }
    header { background: #0d6efd; color: #fff; }
    nav    { background: #f8f9fa; }
    main   { background: #ffffff; }
    aside  { background: #f1f3f5; }
    footer { background: #212529; color:#fff; }
  </style>
</head>
<body class="bg-light">

  {{-- HEADER --}}
  <header class="container-fluid">
    <div class="container d-flex align-items-center justify-content-between">
      <h1 class="h4 m-0">Turismo HYO</h1>
      <div class="small">Contacta: +51 964 652 852</div>
      @php
        $authUser = null;
        if (session()->has('uid')) {
            $authUser = \App\Models\Usuario::find(session('uid'));
        }
      @endphp
    </div>
  </header>

  {{-- NAV --}}
  <nav class="container py-2">
    <ul class="nav">
      <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('catalogo.index') }}">Catálogo</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Personalizar</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
      
      <li class="ms-auto nav-item dropdown">
        @if($authUser)
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Str::limit($authUser->nombre_completo, 20) }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('carrito.index') }}">Mi carrito</a></li>
            <li><a class="dropdown-item" href="{{ route('checkout.index') }}">Checkout</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="post" action="{{ route('auth.logout') }}">
                @csrf
                <button class="dropdown-item">Salir</button>
              </form>
            </li>
          </ul>
        @else
          <a class="nav-link" href="{{ route('auth.login') }}">Ingresar</a>
        @endif
      </li>


    </ul>
  </nav>

  {{-- CONTENIDO --}}
  <div class="container my-3">
    <div class="row g-3">
      {{-- MAIN --}}
      <main class="col-12 col-lg-9">
        @yield('contenido')
      </main>

      {{-- ASIDE (opcional) --}}
      <aside class="col-12 col-lg-3 d-none d-lg-block">
        @yield('aside')
      </aside>
    </div>
  </div>

  {{-- FOOTER --}}
  <footer class="mt-4">
    <div class="container py-3 d-flex flex-column flex-md-row justify-content-between">
      <div>© {{ date('Y') }} Turismo HYO. Todos los derechos reservados.</div>
      <div class="small">
        <a class="text-white text-decoration-none me-3" href="#">Términos</a>
        <a class="text-white text-decoration-none" href="#">Privacidad</a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
