<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ----------------------------
        // 1) Seguridad / Usuarios / Roles
        // ----------------------------
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_completo', 120);
            $table->string('email', 160)->unique();
            $table->string('contrasena_hash', 255);
            $table->string('telefono', 30)->nullable();
            $table->enum('estado', ['activo','inactivo','bloqueado'])->default('activo');
            $table->timestamp('email_verificado_en')->nullable();
            $table->string('recordar_token', 100)->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        Schema::create('usuario_rol', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('rol_id');
            $table->timestamp('asignado_en')->nullable()->useCurrent();
            $table->primary(['usuario_id','rol_id']);
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('perfiles_cliente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->enum('tipo_documento', ['DNI','CE','PASAPORTE'])->nullable();
            $table->string('numero_documento', 30)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->json('preferencias_json')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        // ----------------------------
        // 2) Catalogo
        // ----------------------------
        Schema::create('destinos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 120);
            $table->string('pais', 80)->default('Peru');
            $table->string('region', 120)->nullable();
            $table->string('slug', 160)->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('calificacion_prom', 3, 2)->default(0.00);
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        Schema::create('actividades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destino_id');
            $table->string('nombre', 160);
            $table->string('tipo', 80)->nullable();
            $table->decimal('duracion_horas', 5, 2)->nullable();
            $table->decimal('precio_base', 10, 2)->default(0.00);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('destino_id', 'idx_actividades_destino');
            $table->foreign('destino_id')->references('id')->on('destinos')->onDelete('cascade');
        });

        Schema::create('alojamientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destino_id');
            $table->string('nombre', 160);
            $table->enum('categoria', ['1*','2*','3*','4*','5*'])->default('3*');
            $table->decimal('precio_noche', 10, 2)->default(0.00);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('destino_id', 'idx_aloj_destino');
            $table->foreign('destino_id')->references('id')->on('destinos')->onDelete('cascade');
        });

        Schema::create('transportes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('desde_destino_id')->nullable();
            $table->unsignedBigInteger('hasta_destino_id');
            $table->string('proveedor', 120)->nullable();
            $table->enum('tipo', ['bus','auto','van','vuelo','tren','otros']);
            $table->decimal('precio_base', 10, 2)->default(0.00);
            $table->decimal('duracion_horas', 5, 2)->nullable();
            $table->text('detalles')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('hasta_destino_id', 'idx_transp_hasta');
            $table->foreign('desde_destino_id')->references('id')->on('destinos')->onDelete('set null');
            $table->foreign('hasta_destino_id')->references('id')->on('destinos')->onDelete('cascade');
        });

        // ----------------------------
        // 3) Paquetes
        // ----------------------------
        Schema::create('paquetes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destino_id');
            $table->string('nombre', 180);
            $table->string('slug', 200)->unique();
            $table->string('resumen', 300)->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('dias')->default(1);
            $table->integer('noches')->default(0);
            $table->decimal('precio_desde', 10, 2)->default(0.00);
            $table->boolean('destacado')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('destino_id', 'idx_paquetes_destino');
            $table->foreign('destino_id')->references('id')->on('destinos')->onDelete('cascade');
        });

        Schema::create('paquete_actividades', function (Blueprint $table) {
            $table->unsignedBigInteger('paquete_id');
            $table->unsignedBigInteger('actividad_id');
            $table->integer('cantidad')->default(1);
            $table->primary(['paquete_id', 'actividad_id']);
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');
            $table->foreign('actividad_id')->references('id')->on('actividades')->onDelete('cascade');
        });

        Schema::create('paquete_alojamientos', function (Blueprint $table) {
            $table->unsignedBigInteger('paquete_id');
            $table->unsignedBigInteger('alojamiento_id');
            $table->integer('noches_incluidas')->default(1);
            $table->primary(['paquete_id', 'alojamiento_id']);
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');
            $table->foreign('alojamiento_id')->references('id')->on('alojamientos')->onDelete('cascade');
        });

        Schema::create('paquete_transportes', function (Blueprint $table) {
            $table->unsignedBigInteger('paquete_id');
            $table->unsignedBigInteger('transporte_id');
            $table->primary(['paquete_id', 'transporte_id']);
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');
            $table->foreign('transporte_id')->references('id')->on('transportes')->onDelete('cascade');
        });

        // ----------------------------
        // 4) Personalizaciones / Carrito
        // ----------------------------
        Schema::create('personalizaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('paquete_base_id');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('pax_adultos')->default(1);
            $table->integer('pax_ninos')->default(0);
            $table->json('selecciones_json')->nullable();
            $table->json('desglose_precios_json')->nullable();
            $table->decimal('precio_total', 10, 2)->default(0.00);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('usuario_id', 'idx_pers_usuario');
            $table->index('paquete_base_id', 'idx_pers_paquete');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('paquete_base_id')->references('id')->on('paquetes')->onDelete('cascade');
        });

        Schema::create('carritos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id');
            $table->enum('estado', ['activo','convertido','abandonado'])->default('activo');
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->unique(['usuario_id','estado'], 'uq_carrito_usuario_estado');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        Schema::create('carrito_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('carrito_id');
            $table->unsignedBigInteger('paquete_id');
            $table->unsignedBigInteger('personalizacion_id')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0.00);
            $table->decimal('precio_total', 10, 2)->default(0.00);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('carrito_id', 'idx_ci_carrito');
            $table->index('paquete_id', 'idx_ci_paquete');
            $table->foreign('carrito_id')->references('id')->on('carritos')->onDelete('cascade');
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('restrict');
            $table->foreign('personalizacion_id')->references('id')->on('personalizaciones')->onDelete('set null');
        });

        // ----------------------------
        // 5) Reservas y pagos
        // ----------------------------
        Schema::create('reservas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('codigo', 30)->unique();
            $table->enum('estado', ['pendiente','pagado','cancelado','reembolsado'])->default('pendiente');
            $table->string('contacto_nombre', 160);
            $table->string('contacto_email', 160);
            $table->string('contacto_telefono', 40)->nullable();
            $table->json('pasajeros_json')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('descuento', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('usuario_id', 'idx_reservas_usuario');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('restrict');
        });

        Schema::create('reserva_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reserva_id');
            $table->unsignedBigInteger('paquete_id');
            $table->json('personalizacion_snapshot_json')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0.00);
            $table->decimal('precio_total', 10, 2)->default(0.00);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('reserva_id', 'idx_ri_reserva');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('restrict');
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reserva_id');
            $table->string('pasarela', 50);
            $table->string('moneda', 10)->default('PEN');
            $table->decimal('monto', 10, 2);
            $table->enum('estado', ['iniciado','autorizado','pagado','fallido','reembolsado'])->default('iniciado');
            $table->string('transaccion_ref', 120)->nullable();
            $table->json('payload_json')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->index('reserva_id', 'idx_pagos_reserva');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
        });

        // ----------------------------
        // 6) Cupones
        // ----------------------------
        Schema::create('cupones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 40)->unique();
            $table->enum('tipo', ['porcentaje','fijo']);
            $table->decimal('valor', 10, 2);
            $table->integer('usos_maximos')->nullable();
            $table->integer('usos_actuales')->default(0);
            $table->date('valido_desde')->nullable();
            $table->date('valido_hasta')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        Schema::create('cupon_redenciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cupon_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('reserva_id')->nullable();
            $table->timestamp('redimido_en')->nullable()->useCurrent();
            $table->decimal('monto_aplicado', 10, 2)->default(0.00);
            $table->foreign('cupon_id')->references('id')->on('cupones')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('set null');
        });

        // ----------------------------
        // 7) Reseñas
        // ----------------------------
        Schema::create('resenas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('paquete_id');
            $table->unsignedTinyInteger('calificacion');
            $table->text('comentario')->nullable();
            $table->boolean('moderado')->default(false);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->unique(['usuario_id','paquete_id'], 'uq_resena_usuario_paquete');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');
        });

        // ----------------------------
        // 8) Analítica
        // ----------------------------
        Schema::create('kpi_eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('tipo_evento', 80);
            $table->json('metadatos_json')->nullable();
            $table->timestamp('ocurrido_en')->useCurrent();
            $table->index(['tipo_evento','ocurrido_en'], 'idx_kpi_tipo_fecha');
            $table->index('usuario_id', 'idx_kpi_usuario');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_eventos');
        Schema::dropIfExists('resenas');
        Schema::dropIfExists('cupon_redenciones');
        Schema::dropIfExists('cupones');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('reserva_items');
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('carrito_items');
        Schema::dropIfExists('carritos');
        Schema::dropIfExists('personalizaciones');
        Schema::dropIfExists('paquete_transportes');
        Schema::dropIfExists('paquete_alojamientos');
        Schema::dropIfExists('paquete_actividades');
        Schema::dropIfExists('paquetes');
        Schema::dropIfExists('transportes');
        Schema::dropIfExists('alojamientos');
        Schema::dropIfExists('actividades');
        Schema::dropIfExists('destinos');
        Schema::dropIfExists('perfiles_cliente');
        Schema::dropIfExists('usuario_rol');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
    }
};

