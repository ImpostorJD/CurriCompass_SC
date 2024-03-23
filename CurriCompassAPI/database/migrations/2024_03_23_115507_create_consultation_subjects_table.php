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
        Schema::create('consultation_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('coid');
            $table->unsignedBigInteger('subjectid');

            $table->foreign('coid')
                ->references('coid')
                ->on('consultations')
                ->onDelete('restrict');

            $table->foreign('subjectid')
                ->references('subjectid')
                ->on('subjects')
                ->onDelete('restrict');

            $table->primary(['coid', 'subjectid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_subjects');
    }
};
