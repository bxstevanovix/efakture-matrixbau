<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projekt_budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projekt_id')->constrained('projekts')->cascadeOnDelete();
            $table->foreignId('projekt_record_id')->constrained('projekt_records')->cascadeOnDelete();
            $table->enum('type', ['income', 'expense'])->index();
            $table->string('category')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('entry_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projekt_budget_items');
    }
};
