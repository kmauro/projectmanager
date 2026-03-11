<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('instituciones', function (Blueprint $table) {
            $table->id();
            $table->string('institucion');
        });

        Schema::create('solicitantes', function (Blueprint $table) {
            $table->id();
            $table->string('direccion_IP');
            $table->string('usuario');
            $table->string('nombre_completo');
            $table->string('correo');
            $table->string('telefono');
            $table->string('cuil');
            $table->foreignId('institucion_id')->constrained('instituciones')->onDelete('cascade');
            $table->string('nombre_empresa');
            $table->string('empresa_CUIT');
        });

        Schema::create('niveles_trl', function (Blueprint $table) {
            $table->id();
            $table->string('trl');
        });

        Schema::create('verticales', function (Blueprint $table) {
            $table->id();
            $table->string('vertical');
        });
        
        Schema::create('provincias', function (Blueprint $table){
            $table->id();
            $table->string('provincia');
        });

        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->integer('SID');
            $table->string('titulo');
            $table->dateTime('hora_enviado');
            $table->dateTime('hora_completo');
            $table->dateTime('hora_modificado');
            $table->foreignId('solicitante_id')->constrained('solicitantes')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(true);
            $table->longText('objetivo');
            $table->longText('valor_diferencial');
            $table->foreignId('niveles_trl_id')->constrained('niveles_trl')->onDelete('cascade');
            $table->foreignId('vertical_id')->constrained('verticales')->onDelete('cascade');
            $table->string('entidad_radicado');
            $table->foreignId('provincia_id')->constrained('provincias')->onDelete('cascade');
            $table->string('monto_EBCT')->nullable(true);
        });

        Schema::create('tecnologias', function (Blueprint $table) {
            $table->id();
            $table->string('tecnologia');
        });

        Schema::create('proyectos_tecnologias', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('tecnologia_id')->constrained('tecnologias')->onDelete('cascade');
            $table->string('otra_tecnologia')->nullable(true);
        });

        Schema::create('necesidades', function (Blueprint $table) {
            $table->id();
            $table->string('necesidad');
            $table->foreignId('necesidad_padre_id')->nullable(true)->constrained('necesidades')->onDelete('cascade');
        });

        Schema::create('proyectos_necesidades', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('necesidad_id')->constrained('necesidades')->onDelete('cascade');
            $table->string('otro_equipamiento')->nullable(true);
            $table->string('otra_necesidad')->nullable(true);
        });

        Schema::create('fuentes_financiamiento', function (Blueprint $table) {
            $table->id();
            $table->string('fuente');
            $table->string('tipo');
        });

        Schema::create('proyectos_fuentes_financiamiento', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('fuente_id')->constrained('fuentes_financiamiento')->onDelete('cascade');
            $table->string('otra_fuente')->nullable(true);
            $table->float('porcentaje');
            $table->integer('monto');
        });

        Schema::create('sectores', function (Blueprint $table) {
            $table->id();
            $table->string('sector');
            $table->foreignId('sector_padre_id')->nullable(true)->constrained('sectores')->onDelete('cascade');
        });

        Schema::create('proyectos_sectores', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituciones');
        Schema::dropIfExists('solicitantes');
        Schema::dropIfExists('niveles_trl');
        Schema::dropIfExists('verticales');
        Schema::dropIfExists('tecnologias');
        Schema::dropIfExists('provincias');
        Schema::dropIfExists('proyectos');
        Schema::dropIfExists('necesidades');
        Schema::dropIfExists('proyectos_necesidades');
        Schema::dropIfExists('fuentes_financiamiento');
        Schema::dropIfExists('proyectos_fuentes_financiamiento');
        Schema::dropIfExists('sectores');
        Schema::dropIfExists('proyectos_sectores');
    }
};
