<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom is_active dengan nilai default true
            $table->boolean('is_active')->default(true)->after('role');
            
            // Catatan: Pastikan kolom 'role' lu bertipe string atau enum 
            // yang bisa menerima nilai 'superadmin', 'admin', dan 'user'.
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};