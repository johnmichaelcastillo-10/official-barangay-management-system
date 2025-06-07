<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejected_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->enum('civil_status', ['single', 'married', 'widowed', 'divorced']);
            $table->string('occupation')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('photo')->nullable();
            $table->string('valid_id')->nullable();
            $table->enum('registration_type', ['public', 'admin'])->default('public');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('rejected_at')->nullable(); // CHANGED: Added nullable()
            $table->unsignedBigInteger('rejected_by')->nullable(); // CHANGED: Added nullable()
            $table->text('rejection_reason')->nullable(); // CHANGED: Added nullable()
            $table->timestamps();

            // Indexes for better performance
            $table->index(['rejected_at', 'rejected_by']);
            $table->index('reference_number');
            $table->index(['first_name', 'last_name', 'birth_date']);

            // Foreign key constraint
            $table->foreign('rejected_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rejected_registrations');
    }
};
