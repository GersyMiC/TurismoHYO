<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Usuario;
use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Reserva;
use App\Models\ReservaItem;
use App\Models\Pago;
use App\Models\Cupon;
use App\Models\CuponRedencion;

class CheckoutController extends Controller
{
    protected function usuarioId(): int
    {
        $id = (int) (Usuario::value('id') ?? 0);
        abort_if($id === 0, 500, 'No hay usuarios disponibles.');
        return $id;
    }

    protected function carritoActivo(int $usuarioId): Carrito
    {
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

        if ($carrito->items->isEmpty()) {
            return redirect()->route('carrito.index')->with('ok', 'Tu carrito está vacío.');
        }

        $total = $carrito->items->sum('precio_total');

        return view('checkout.index', compact('carrito', 'total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contacto_nombre'  => 'required|string|max:160',
            'contacto_email'   => 'required|email|max:160',
            'contacto_telefono'=> 'nullable|string|max:40',
            'codigo_cupon'     => 'nullable|string|max:40',
            'acepto_terminos'  => 'accepted',
            'metodo_pago'      => 'required|in:efectivo,tarjeta', // simulado
        ]);

        $uid = $this->usuarioId();
        $carrito = $this->carritoActivo($uid)->load(['items.paquete','items.personalizacion']);

        if ($carrito->items->isEmpty()) {
            return redirect()->route('carrito.index')->with('ok', 'Tu carrito está vacío.');
        }

        // Montos base
        $subtotal = (float) $carrito->items->sum('precio_total');
        $descuento = 0.0;
        $cupon = null;

        // Valida cupón (opcional)
        if (!empty($data['codigo_cupon'])) {
            $codigo = strtoupper(trim($data['codigo_cupon']));
            $cupon = Cupon::where('codigo', $codigo)
                ->where('activo', true)
                ->where(function($q){
                    $hoy = now()->toDateString();
                    $q->whereNull('valido_desde')->orWhere('valido_desde', '<=', $hoy);
                })
                ->where(function($q){
                    $hoy = now()->toDateString();
                    $q->whereNull('valido_hasta')->orWhere('valido_hasta', '>=', $hoy);
                })
                ->first();

            if ($cupon) {
                if ($cupon->usos_maximos !== null && $cupon->usos_actuales >= $cupon->usos_maximos) {
                    $cupon = null; // sin cupo
                } else {
                    if ($cupon->tipo === 'porcentaje') {
                        $descuento = round(min($subtotal, $subtotal * ($cupon->valor/100)), 2);
                    } else { // fijo
                        $descuento = round(min($subtotal, (float)$cupon->valor), 2);
                    }
                }
            }
        }

        $total = max(0, round($subtotal - $descuento, 2));

        // Rango de fechas de la reserva (si hay personalizaciones con fechas)
        $fechaInicio = null; $fechaFin = null;
        foreach ($carrito->items as $it) {
            if ($it->personalizacion) {
                if ($it->personalizacion->fecha_inicio && (!$fechaInicio || $it->personalizacion->fecha_inicio < $fechaInicio)) {
                    $fechaInicio = $it->personalizacion->fecha_inicio;
                }
                if ($it->personalizacion->fecha_fin && (!$fechaFin || $it->personalizacion->fecha_fin > $fechaFin)) {
                    $fechaFin = $it->personalizacion->fecha_fin;
                }
            }
        }

        $codigoReserva = 'HYO-'.now()->format('ymd').'-'.Str::upper(Str::random(5));

        $reserva = DB::transaction(function () use (
            $uid, $carrito, $codigoReserva, $data, $subtotal, $descuento, $total, $fechaInicio, $fechaFin, $cupon
        ) {
            // 1) Crear reserva
            $reserva = Reserva::create([
                'usuario_id'        => $uid,
                'codigo'            => $codigoReserva,
                'estado'            => 'pendiente',
                'contacto_nombre'   => $data['contacto_nombre'],
                'contacto_email'    => $data['contacto_email'],
                'contacto_telefono' => $data['contacto_telefono'] ?? null,
                'pasajeros_json'    => null,
                'subtotal'          => $subtotal,
                'descuento'         => $descuento,
                'total'             => $total,
                'fecha_inicio'      => $fechaInicio,
                'fecha_fin'         => $fechaFin,
                'creado_en'         => now(),
                'actualizado_en'    => now(),
            ]);

            // 2) Items
            foreach ($carrito->items as $it) {
                $snapshot = null;
                if ($it->personalizacion) {
                    $snapshot = [
                        'selecciones' => $it->personalizacion->selecciones_json,
                        'desglose'    => $it->personalizacion->desglose_precios_json,
                        'pax_adultos' => $it->personalizacion->pax_adultos,
                        'pax_ninos'   => $it->personalizacion->pax_ninos,
                        'fechas'      => [
                            'inicio' => $it->personalizacion->fecha_inicio,
                            'fin'    => $it->personalizacion->fecha_fin,
                        ],
                    ];
                }

                ReservaItem::create([
                    'reserva_id'                    => $reserva->id,
                    'paquete_id'                    => $it->paquete_id,
                    'personalizacion_snapshot_json' => $snapshot,
                    'cantidad'                      => $it->personalizacion_id ? 1 : $it->cantidad,
                    'precio_unitario'               => $it->precio_unitario,
                    'precio_total'                  => $it->personalizacion_id ? $it->precio_total : round($it->precio_unitario * $it->cantidad, 2),
                    'creado_en'                     => now(),
                    'actualizado_en'                => now(),
                ]);
            }

            // 3) Pago (simulado)
            Pago::create([
                'reserva_id'     => $reserva->id,
                'pasarela'       => $data['metodo_pago'] === 'tarjeta' ? 'tarjeta_simulada' : 'efectivo',
                'moneda'         => 'PEN',
                'monto'          => $reserva->total,
                'estado'         => 'iniciado', // luego podremos actualizar a 'pagado'
                'transaccion_ref'=> null,
                'payload_json'   => ['nota' => 'Pago simulado en checkout'],
                'creado_en'      => now(),
                'actualizado_en' => now(),
            ]);

            // 4) Cupón (si aplica)
            if ($cupon && $reserva->descuento > 0) {
                CuponRedencion::create([
                    'cupon_id'       => $cupon->id,
                    'usuario_id'     => $reserva->usuario_id,
                    'reserva_id'     => $reserva->id,
                    'redimido_en'    => now(),
                    'monto_aplicado' => $reserva->descuento,
                ]);
                $cupon->increment('usos_actuales');
            }

            // 5) Convertir carrito y limpiar items
            $carrito->estado = 'convertido';
            $carrito->actualizado_en = now();
            $carrito->save();

            CarritoItem::where('carrito_id', $carrito->id)->delete();

            return $reserva;
        });

        return redirect()->route('reserva.show', $reserva->codigo)
                         ->with('ok', 'Reserva creada correctamente.');
    }

    public function show(string $codigo)
    {
        $reserva = Reserva::with(['items.paquete.destino','pagos'])->where('codigo',$codigo)->firstOrFail();
        return view('checkout.confirm', compact('reserva'));
    }
}
