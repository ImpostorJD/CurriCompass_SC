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
        Schema::create('subjects_taken', function (Blueprint $table) {
            $table->unsignedBigInteger('srid');
            $table->unsignedBigInteger('subjectid');
            $table->unsignedBigInteger('taken_at');
            $table->string('remark'); //subjected to change
            $table->foreign('srid')
            ->references('srid')
            ->on('student_records')
            ->onDelete('cascade');
        $table->foreign('subjectid')
            ->references('subjectid')
            ->on('subjects')
            ->onDelete('cascade');
        $table->foreign('taken_at')
            ->references('semid')
            ->on('semesters')
            ->onDelete('cascade');
        $table->primary(['srid', 'subjectid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects_taken');
    }
};
