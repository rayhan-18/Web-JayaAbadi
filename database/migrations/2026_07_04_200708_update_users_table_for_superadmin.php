<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Ubah kolom role menjadi string (VARCHAR) agar fleksibel menampung 'superadmin'
            // Pastikan kamu sudah menjalankan 'composer require doctrine/dbal' jika pakai Laravel versi lama
            $table->string('role')->default('user')->change();
            
            // 2. Tambahkan kolom is_active jika belum ada
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
            // Revert role ke kondisi awal jika di-rollback (sesuaikan dengan tipe awalmu)
        });
    }
};