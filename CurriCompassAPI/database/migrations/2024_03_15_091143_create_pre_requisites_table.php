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
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year'])->nullable();
            //$table->double('completion')->nullable();
            $table->foreign('subjectid')
                ->references('subjectid')
                ->on('subjects')
                ->onDelete('cascade');

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
