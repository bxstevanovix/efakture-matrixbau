<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projekt_record_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projekt_record_id')->constrained('projekt_records')->cascadeOnDelete();
            $table->enum('file_type', ['document', 'invoice']);
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['projekt_record_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projekt_record_files');
    }
};
