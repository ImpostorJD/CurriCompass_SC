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
        Schema::create('student_records', function (Blueprint $table) {
            $table->id('srid');
            $table->string('student_no')->unique();
            $table->unsignedBigInteger('year_level_id')->nullable();
            $table->enum('status', ['Regular', 'Irregular', 'Graduated', 'Inactive'])->default('regular');
            $table->unsignedBigInteger('userid');
            $table->unsignedBigInteger('sy')->nullable();
            $table->unsignedBigInteger('cid')->nullable();
            $table->timestamps();
            $table->foreign('userid')
                ->references('userid')
                ->on('users')
                ->unique()
                ->onDelete('cascade');
            $table->foreign('sy')
                ->references('sy')
                ->on('school_years')
                ->onDelete('restrict');
            $table->foreign('cid')
                ->references('cid')
                ->on('curricula')
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
        Schema::dropIfExists('student_records');
    }
};
