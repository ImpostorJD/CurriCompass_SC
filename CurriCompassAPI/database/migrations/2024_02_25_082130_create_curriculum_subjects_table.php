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
        Schema::create('curriculum_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('cid');
            $table->unsignedBigInteger('semid');
            $table->string('coursecode');
            $table->string('coursedescription');
            $table->double('units');
            $table->double('unitslab');
            $table->double('unitslec');
            $table->double('hourslec');
            $table->double('hourslab');
            $table->string('prerequisites')->nullable();
            $table->unsignedBigInteger('year_level_id');
            $table->foreign('cid')
                ->references('cid')
                ->on('curricula')
                ->onDelete('cascade');

            $table->foreign('semid')
                ->references('semid')
                ->on('semesters')
                ->onDelete('cascade');
            $table->foreign('year_level_id')
                ->references('year_level_id')
                ->on('year_levels')
                ->onDelete('restrict');

            $table->primary(['cid', 'coursecode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_subjects');
    }
};
