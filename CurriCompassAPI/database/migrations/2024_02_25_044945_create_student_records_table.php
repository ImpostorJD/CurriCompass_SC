<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//TODO: add connection to school year table
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_records', function (Blueprint $table) {
            $table->id('srid');
            $table->string('student_no')->unique();
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year'])->nullable();
            $table->enum('status', ['Regular', 'Irregular', 'Graduated', 'Inactive'])->default('regular');
            $table->unsignedBigInteger('userid');
            $table->unsignedBigInteger('cid')->nullable();
            $table->timestamps();
            $table->foreign('userid')
                ->references('userid')
                ->on('users')
                ->unique()
                ->onDelete('cascade');
            $table->foreign('cid')
                ->references('cid')
                ->on('curricula')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};
