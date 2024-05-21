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
        Schema::create('enlistment_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('peid');
            $table->unsignedBigInteger('caid');

            $table->primary(['peid','caid']);
            $table->foreign('peid')
                ->references('peid')
                ->on('enlistments')
                ->onDelete('cascade');

            $table->foreign('caid')
                ->references('caid')
                ->on('course_availabilities')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enlistment_subjects');
    }
};
