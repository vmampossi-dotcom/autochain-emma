<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('maintenance_type');
            $table->string('repairer_name');
            $table->integer('mileage');
            $table->date('performed_at');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->boolean('critical')->default(false);
            $table->string('proof_hash')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_entries');
    }
};
