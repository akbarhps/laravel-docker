<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_keycloak', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('sub', 36);
            $table->string('kode_identitas', 36);
            $table->string('mobile', 16);
            $table->string('nik')->nullable();
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->json('user_group')->nullable();

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_keycloak');
    }
};
