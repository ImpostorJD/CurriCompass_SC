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
            $table->id('caid');
            $table->unsignedBigInteger('subjectid');
            $table->unsignedBigInteger('semsyid');
            $table->enum('time', ['8-10', '8-11','10-12', '11-2', '1-3','2-5', '3-5']);
            $table->string('section');
            $table->integer('section_limit')->default(0);
            $table->enum('days', ['M-Th', 'T,F', 'W,S']);

            $table->unique(['subjectid', 'semsyid', 'time','section','days']);
            $table->foreign('subjectid')
                ->references('subjectid')
                ->on('subjects')
                ->onDelete('cascade');

            $table->foreign('semsyid')
                ->references('semsyid')
                ->on('sem_sy')
                ->onDelete('cascade');
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
