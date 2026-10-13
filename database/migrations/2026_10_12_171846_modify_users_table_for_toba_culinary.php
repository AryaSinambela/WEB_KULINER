<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'owner', 'user'])->default('user')->after('email');
            $table->enum('user_origin', ['local', 'tourist'])->default('tourist')->after('role');
            $table->string('phone_number')->nullable()->after('user_origin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'user_origin', 'phone_number']);
        });
    }
};