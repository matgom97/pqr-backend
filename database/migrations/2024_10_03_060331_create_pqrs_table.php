<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqrsTable extends Migration
{
    public function up()
    {
        Schema::create('pqrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('fecha_incidencia');
            $table->string('identificacion');
            $table->string('primer_nombre');
            $table->string('primer_apellido');
            $table->string('correo')->nullable();
            $table->string('medio_notificacion')->nullable();
            $table->enum('tipo', ['Petición', 'Queja', 'Reclamo']);
            $table->text('causas')->nullable();
            $table->text('observacion');
            $table->text('evidencias')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pqrs');
    }
}