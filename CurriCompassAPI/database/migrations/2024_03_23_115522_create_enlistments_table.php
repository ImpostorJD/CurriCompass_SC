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
        Schema::create('enlistments', function (Blueprint $table) {
            $table->id('peid');
            $table->unsignedBigInteger('srid');
            $table->unsignedBigInteger('cid');
            $table->unsignedBigInteger('year_level_id');
            $table->unsignedBigInteger('semsyid');

            $table->unique([
                'srid',
                'cid',
                'year_level_id',
                'semsyid'
            ]);

            $table->foreign('srid')
                ->references('srid')
                ->on('student_records')
                ->onDelete('cascade');

            $table->foreign('cid')
                ->references('cid')
                ->on('curricula')
                ->onDelete('restrict');

            $table->foreign('year_level_id')
                ->references('year_level_id')
                ->on('year_levels')
                ->onDelete('restrict');
            $table->foreign('semsyid')
                ->references('semsyid')
                ->on('sem_sy')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enlistments');
    }
};
