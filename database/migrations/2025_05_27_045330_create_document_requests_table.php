<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->string('document_type');
            $table->string('purpose');
            $table->enum('status', ['pending', 'processing', 'ready', 'released', 'rejected', 'received'])->default('pending');
            $table->text('remarks')->nullable();
            $table->decimal('fee_amount', 8, 2)->default(0.00);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('requested_date');
            $table->date('target_release_date')->nullable();
            $table->date('actual_release_date')->nullable();
            $table->string('tracking_number')->unique();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_requests');
    }
};
