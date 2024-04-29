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
        Schema::create('authentication_tokens', function (Blueprint $table) {
            $table->id('id_authentication_token');
            $table->foreignId('id_user')->constrained('users');
            $table->string('token', 100);
            $table->timestamp('expires_at');
            $table->timestamp('created_at')->default(now());
            $table->tinyInteger('expired')->default(0);
            $table->timestamps();

            $table->primary('id_authentication_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication_tokens');
    }
};
