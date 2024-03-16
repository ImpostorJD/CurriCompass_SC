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
        Schema::create('curriculum_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('cid');
            $table->unsignedBigInteger('subjectid');
            $table->unsignedBigInteger('semid');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year']);
            $table->foreign('cid')
                ->references('cid')
                ->on('curricula')
                ->onDelete('cascade');
            $table->foreign('subjectid')
                ->references('subjectid')
                ->on('subjects')
                ->onDelete('cascade');
            $table->foreign('semid')
                ->references('semid')
                ->on('semesters')
                ->onDelete('cascade');
            $table->primary(['cid', 'subjectid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_subjects');
    }
};
