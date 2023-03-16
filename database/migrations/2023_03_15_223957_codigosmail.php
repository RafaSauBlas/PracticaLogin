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
        Schema::create('codigo_mails', function (Blueprint $table) {
            $table->string('codigomail')->nullable();
            $table->timestamp('codigomail_created_at')->nullable();
            $table->timestamp('codigomail_verified_at')->nullable();
            $table->integer('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_mails');
    }
};
