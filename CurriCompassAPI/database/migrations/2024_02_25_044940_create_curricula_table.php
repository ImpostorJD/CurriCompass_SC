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
        Schema::create('curricula', function (Blueprint $table) {
            $table->id('cid');
            $table->unsignedBigInteger('programid');
            $table->string('specialization')
                ->nullable();
            $table->timestamps();
            $table->unique([
                'specialization',
                'programid',
            ]);
            $table->foreign('programid')
                ->references('programid')
                ->on('programs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};
