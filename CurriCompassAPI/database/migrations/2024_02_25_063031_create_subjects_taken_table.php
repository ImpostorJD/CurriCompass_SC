<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//TODO: Connect school year to table school year
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subjects_taken', function (Blueprint $table) {
            $table->unsignedBigInteger('srid');
            $table->string('coursecode');
            $table->string('grade')->nullable();
            $table->foreign('srid')
                ->references('srid')
                ->on('student_records')
                ->onDelete('cascade');
            $table->primary(['srid', 'coursecode']);
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
