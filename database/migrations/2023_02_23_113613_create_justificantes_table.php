<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJustificantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('justificantes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nif');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->integer('cp');
            $table->string('email');
            $table->integer('tlf');
            $table->timestamp('date');
            $table->decimal('amount', 8, 2);
            $table->integer('percentage');
            $table->unsignedBigInteger('cobro_id');
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
        Schema::dropIfExists('justificantes');
    }
}
