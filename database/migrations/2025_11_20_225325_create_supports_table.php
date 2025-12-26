<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('number');
            $table->string('detail')->nullable();
            $table->string('comments')->nullable();
            $table->decimal('total', 8, 2)->default('0.00');
            $table->string('attached_file')->nullable();
            $table->foreignId('customer_id')->constrained();
            $table->enum('payment_status', ['paid', 'pending', 'partial'])->default('pending');
            $table->unsignedBigInteger('establishment_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};
