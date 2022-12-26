<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division', function (Blueprint $table) {
            $table->id();
            //campo nombre de tipo string de longitud 45
            $table->string('nombre', 45);
            //campo nivel entero
            $table->integer('nivel');
            //campo colaboradores entero
            $table->integer('colaboradores');
            //campo embajador string
            $table->string('embajador', 100);
            //campo parent_id entero y clave foranea de la tabla division con id (puede ser nulo)
            $table->foreignId('parent_id')->nullable()->constrained('division')->onDelete('set null');
            //campo created_at y updated_at
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('division');
    }
};
