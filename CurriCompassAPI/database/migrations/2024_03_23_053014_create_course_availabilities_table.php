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
        Schema::create('course_availabilities', function (Blueprint $table) {
        $table->unsignedBigInteger('subjectid')->unique();
            $table->unsignedBigInteger('semid');

            $table->foreign('subjectid')
                ->references('subjectid')
                ->on('subjects')
                ->onDelete('cascade');

            $table->foreign('semid')
                ->references('semid')
                ->on('semesters')
                ->onDelete('cascade');

            $table->primary(['semid', 'subjectid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_availabilities');
    }
};
