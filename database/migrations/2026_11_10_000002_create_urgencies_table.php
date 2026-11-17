<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('urgencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            // Tingkat urutan: 1 = paling rendah, makin besar makin mendesak.
            $table->unsignedTinyInteger('level')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urgencies');
    }
};