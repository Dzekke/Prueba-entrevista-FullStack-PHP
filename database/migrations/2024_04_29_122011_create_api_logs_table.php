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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->text('request_data');
            $table->text('response_data');
            $table->timestamp('created_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
