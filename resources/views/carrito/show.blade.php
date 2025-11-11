<div class="d-flex gap-2">
  <a href="{{ route('paquete.show', $p->paqueteBase->slug) }}" class="btn btn-outline-secondary">Volver al paquete</a>

  <form method="post" action="{{ route('carrito.agregar') }}">
    @csrf
    <input type="hidden" name="paquete_id" value="{{ $p->paqueteBase->id }}">
    <input type="hidden" name="personalizacion_id" value="{{ $p->id }}">
    <button class="btn btn-primary">Agregar al carrito</button>
  </form>

  <a href="{{ route('carrito.index') }}" class="btn btn-outline-primary">Ver carrito</a>
</div>
