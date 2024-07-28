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
        // Schema::create('pre__requisites__subjects', function (Blueprint $table) {
        //     $table->unsignedBigInteger('subjectid');
        //     $table->unsignedBigInteger('prid');
        //     $table->primary(['subjectid', 'prid']);
        //     $table->foreign('subjectid')
        //         ->references('subjectid')
        //         ->on('subjects')
        //         ->onDelete('cascade');
        //     $table->foreign('prid')
        //         ->references('prid')
        //         ->on('pre__requisites')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre__requisites__subjects');
    }
};
