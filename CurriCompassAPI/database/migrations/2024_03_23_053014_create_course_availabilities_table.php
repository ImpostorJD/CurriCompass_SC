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
            $table->string('coursecode');
            $table->unsignedBigInteger('semsyid');
            $table->boolean('lab');
            $table->enum('time', ['8-10', '8-11', '9-11', '10-12', '11-2', '1-3', '2-4','2-5', '3-5']);
            $table->string('section');
            $table->integer('section_limit')->default(0);
            $table->enum('days', ['M-Th', 'T-F', 'W-S']);

            $table->unique(['coursecode', 'semsyid', 'time','section','days']);
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
