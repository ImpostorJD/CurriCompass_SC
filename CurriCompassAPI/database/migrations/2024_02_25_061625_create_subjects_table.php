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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subjectid');
            $table->string('subjectcode')->unique();
            $table->string('subjectname');
            $table->integer('subjectcredits');
            $table->integer('subjectunitlec');
            $table->integer('subjectunitlab');
            $table->double('subjecthourslec');
            $table->double('subjecthourslab');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
