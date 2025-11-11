<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Paquete;
use App\Models\Personalizacion;

class CarritoController extends Controller
{
    protected function usuarioId(): int
    {
        // Por ahora sin auth: toma el primer usuario (ej. admin del seeder)
        $id = (int) (Usuario::value('id') ?? 0);
        abort_if($id === 0, 500, 'No hay usuarios disponibles.');
        return $id;
    }

    protected function carritoActivo(int $usuarioId): Carrito
    {
        // Respeta la restricción única (usuario_id, estado)
        return Carrito::firstOrCreate(
            ['usuario_id' => $usuarioId, 'estado' => 'activo'],
            ['creado_en' => now(), 'actualizado_en' => now()]
        );
    }

    public function index()
    {
        $uid = $this->usuarioId();
        $carrito = $this->carritoActivo($uid)->load([
            'items.paquete.destino',
            'items.personalizacion.paqueteBase.destino'
        ]);

        $total = $carrito->items->sum('precio_total');

        return view('carrito.index', compact('carrito', 'total'));
    }

    public function agregar(Request $request)
    {
        $data = $request->validate([
            'paquete_id'         => 'required|integer|exists:paquetes,id',
            'personalizacion_id' => 'nullable|integer|exists:personalizaciones,id',
            'cantidad'           => 'nullable|integer|min:1',
        ]);

        $uid = $this->usuarioId();
        $carrito = $this->carritoActivo($uid);

        $paquete = Paquete::findOrFail($data['paquete_id']);
        $personalizacion = null;
        $cantidad = (int)($data['cantidad'] ?? 1);

        // Precio unitario
        if (!empty($data['personalizacion_id'])) {
            $personalizacion = Personalizacion::findOrFail($data['personalizacion_id']);
            // La personalización ya incluye pax/selecciones → se trata como “unidad” cerrada
            $precioUnitario = (float)$personalizacion->precio_total;
            $cantidad = 1; // no multiplicamos personalizaciones por cantidad
        } else {
            // Sin personalización: tratamos cantidad como #personas
            $precioUnitario = (float)$paquete->precio_desde;
        }

        // ¿Ya existe un item igual? (mismo paquete + misma personalización)
        $item = CarritoItem::where('carrito_id', $carrito->id)
            ->where('paquete_id', $paquete->id)
            ->where('personalizacion_id', $personalizacion->id ?? null)
            ->first();

        if ($item) {
            // Sumamos cantidades y recalculamos total
            $nuevaCantidad = (int)$item->cantidad + $cantidad;
            // OJO: si viene de personalización, dejamos cantidad = 1 (no tiene sentido replicar)
            if ($personalizacion) $nuevaCantidad = 1;

            $item->cantidad = $nuevaCantidad;
            $item->precio_unitario = $precioUnitario;
            $item->precio_total = round($precioUnitario * $item->cantidad, 2);
            $item->actualizado_en = now();
            $item->save();
        } else {
            CarritoItem::create([
                'carrito_id'         => $carrito->id,
                'paquete_id'         => $paquete->id,
                'personalizacion_id' => $personalizacion?->id,
                'cantidad'           => $cantidad,
                'precio_unitario'    => $precioUnitario,
                'precio_total'       => round($precioUnitario * $cantidad, 2),
                'creado_en'          => now(),
                'actualizado_en'     => now(),
            ]);
        }

        return redirect()->route('carrito.index')->with('ok', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, int $item)
    {
        $data = $request->validate([
            'cantidad' => 'required|integer|min:0',
        ]);

        $item = CarritoItem::with('personalizacion')->findOrFail($item);

        // Si es personalización, forzamos cantidad 1 (ya incluye pax)
        if ($item->personalizacion_id) {
            if ($data['cantidad'] == 0) {
                $item->delete();
                return back()->with('ok', 'Item eliminado.');
            }
            $item->cantidad = 1;
        } else {
            if ($data['cantidad'] == 0) {
                $item->delete();
                return back()->with('ok', 'Item eliminado.');
            }
            $item->cantidad = (int)$data['cantidad'];
        }

        $item->precio_total = round($item->precio_unitario * $item->cantidad, 2);
        $item->actualizado_en = now();
        $item->save();

        return back()->with('ok', 'Carrito actualizado.');
    }

    public function eliminar(int $item)
    {
        CarritoItem::where('id', $item)->delete();
        return back()->with('ok', 'Item eliminado.');
    }

    public function vaciar()
    {
        $uid = $this->usuarioId();
        $carrito = $this->carritoActivo($uid);
        CarritoItem::where('carrito_id', $carrito->id)->delete();
        return back()->with('ok', 'Carrito vaciado.');
    }
}
