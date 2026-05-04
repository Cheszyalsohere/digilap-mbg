<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sppg_id')->constrained('sppgs')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('slot_1');
            $table->string('slot_2');
            $table->string('slot_3');
            $table->string('slot_4');
            $table->string('slot_5');
            $table->timestamps();

            $table->unique(['sppg_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
