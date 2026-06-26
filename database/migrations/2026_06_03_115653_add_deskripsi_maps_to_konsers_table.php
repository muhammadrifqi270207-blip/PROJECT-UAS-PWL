<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsers', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('poster');
            $table->string('maps_url')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('konsers', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'maps_url']);
        });
    }
};