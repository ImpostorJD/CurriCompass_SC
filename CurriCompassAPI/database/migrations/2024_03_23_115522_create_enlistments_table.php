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
        Schema::create('enlistments', function (Blueprint $table) {
            $table->id('peid');
            $table->unsignedBigInteger('srid');
            $table->unsignedBigInteger('coid');

            $table->foreign('srid')
                ->references('srid')
                ->on('student_records')
                ->onDelete('restrict');

            $table->foreign('coid')
                ->references('coid')
                ->on('consultations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enlistments');
    }
};
