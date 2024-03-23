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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id('coid');
            $table->unsignedBigInteger('semid');
            $table->unsignedBigInteger('sy');
            $table->unsignedBigInteger('cid');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year']);
            $table->unsignedBigInteger('srid')->nullable();

            $table->foreign('semid')
                ->references('semid')
                ->on('semesters')
                ->onDelete('restrict');

            $table->foreign('sy')
                ->references('sy')
                ->on('school_years')
                ->onDelete('restrict');

            $table->foreign('cid')
                ->references('cid')
                ->on('curricula')
                ->onDelete('restrict');

            $table->foreign('srid')
                ->references('srid')
                ->on('student_records')
                ->onDelete('restrict');

            $table->unique(['sy', 'cid', 'semid','srid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
