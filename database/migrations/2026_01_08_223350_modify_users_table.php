<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // تغيير email إلى phone
            $table->renameColumn('email', 'phone');

            // حذف email_verified_at
            $table->dropColumn('email_verified_at');
        });

        Schema::table('users', function (Blueprint $table) {
            // جعل name و phone unique
            $table->unique('name');
            $table->unique('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // الرجوع كما كان
            $table->renameColumn('phone', 'email');
            $table->timestamp('email_verified_at')->nullable();

            $table->dropUnique(['name']);
            $table->dropUnique(['phone']);
        });
    }
};
