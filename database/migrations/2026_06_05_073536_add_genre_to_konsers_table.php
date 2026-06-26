<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsers', function (Blueprint $table) {
            $table->string('genre')->nullable()->after('artis');
        });
    }

    public function down(): void
    {
        Schema::table('konsers', function (Blueprint $table) {
            $table->dropColumn('genre');
        });
    }
};