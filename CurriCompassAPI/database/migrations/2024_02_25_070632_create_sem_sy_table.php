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
        Schema::create('sem_sy', function (Blueprint $table) {
            $table->id('semsyid');
            $table->unsignedBigInteger('semid');
            $table->unsignedBigInteger('sy');

            $table->unique(['semid', 'sy']);

            $table->foreign('semid')
                ->references('semid')
                ->on('semesters')
                ->onDelete('restrict');

            $table->foreign('sy')
                ->references('sy')
                ->on('school_years')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sem_sy');
    }
};
