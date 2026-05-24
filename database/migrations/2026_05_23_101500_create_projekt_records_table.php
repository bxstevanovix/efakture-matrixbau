<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projekt_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projekt_id')->constrained('projekts')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('report')->nullable();
            $table->integer('position')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['projekt_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projekt_records');
    }
};
