<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user__roles', function (Blueprint $table) {
            $table->unsignedBigInteger('userid');
            $table->unsignedBigInteger('roleid');
            $table->foreign('userid')
                ->references('userid')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('roleid')
                ->references('roleid')
                ->on('roles')
                ->onDelete('cascade');
            $table->primary(['userid', 'roleid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user__roles');
    }
};
