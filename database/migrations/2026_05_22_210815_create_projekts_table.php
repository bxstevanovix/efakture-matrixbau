<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projekts', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('planned_budget', 12, 2)->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projekts');
    }
};
