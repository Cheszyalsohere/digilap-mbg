<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->boolean('has_alternatif')->default(false)->after('foto_menu');
            $table->string('alt_slot_1')->nullable()->after('has_alternatif');
            $table->string('alt_slot_2')->nullable()->after('alt_slot_1');
            $table->string('alt_slot_3')->nullable()->after('alt_slot_2');
            $table->string('alt_slot_4')->nullable()->after('alt_slot_3');
            $table->string('alt_slot_5')->nullable()->after('alt_slot_4');
            $table->string('alt_keterangan')->nullable()->after('alt_slot_5');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn([
                'has_alternatif',
                'alt_slot_1', 'alt_slot_2', 'alt_slot_3', 'alt_slot_4', 'alt_slot_5',
                'alt_keterangan',
            ]);
        });
    }
};
