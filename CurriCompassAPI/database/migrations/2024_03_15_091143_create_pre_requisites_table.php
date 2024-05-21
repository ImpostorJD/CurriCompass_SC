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
        Schema::create('pre__requisites', function (Blueprint $table) {
            $table->id('prid');
            $table->unsignedBigInteger('subjectid')->unique();
            $table->unsignedBigInteger('year_level_id')->nullable();
            //$table->double('completion')->nullable();
            $table->foreign('subjectid')
                ->references('subjectid')
                ->on('subjects')
                ->onDelete('cascade');

            $table->foreign('year_level_id')
                ->references('year_level_id')
                ->on('year_levels')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre__requisites');
    }
};
