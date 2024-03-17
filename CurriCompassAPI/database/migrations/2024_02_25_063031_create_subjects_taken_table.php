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
            $table->unsignedBigInteger('subjectid');
            $table->enum('taken_at', ['Sem 1', 'Sem 2', 'Sem 3', 'Credited']);
           // $table->string('school_year');
            $table->enum('remark', ["Excelent", "Very Good", "Good", "Fair", "Passing", "Failed", "Widthdrawn", "Incomplete", "None"]);
            $table->foreign('srid')
                ->references('srid')
                ->on('student_records')
                ->onDelete('cascade');
            $table->foreign('subjectid')
                ->references('subjectid')
                ->on('subjects')
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
