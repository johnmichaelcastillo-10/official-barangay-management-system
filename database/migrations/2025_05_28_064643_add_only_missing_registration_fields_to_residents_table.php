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
        Schema::table('residents', function (Blueprint $table) {
            // Add only missing columns

            if (!Schema::hasColumn('residents', 'registration_type')) {
                $table->enum('registration_type', ['admin', 'public'])->default('public')->after('status');
            }

            if (!Schema::hasColumn('residents', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable();
            }

            if (!Schema::hasColumn('residents', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }

            if (!Schema::hasColumn('residents', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }

            if (!Schema::hasColumn('residents', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            }

            if (!Schema::hasColumn('residents', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable();
            }

            if (!Schema::hasColumn('residents', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });

        // Add foreign key constraints
        Schema::table('residents', function (Blueprint $table) {
            // Check if foreign keys don't already exist
            $foreignKeys = $this->getExistingForeignKeys('residents');

            if (Schema::hasColumn('residents', 'approved_by') && !in_array('residents_approved_by_foreign', $foreignKeys)) {
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            }

            if (Schema::hasColumn('residents', 'rejected_by') && !in_array('residents_rejected_by_foreign', $foreignKeys)) {
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Drop foreign keys first
            $foreignKeys = $this->getExistingForeignKeys('residents');

            if (in_array('residents_approved_by_foreign', $foreignKeys)) {
                $table->dropForeign(['approved_by']);
            }

            if (in_array('residents_rejected_by_foreign', $foreignKeys)) {
                $table->dropForeign(['rejected_by']);
            }

            // Drop columns that we added
            $columnsToDrop = [];

            if (Schema::hasColumn('residents', 'registration_type')) {
                $columnsToDrop[] = 'registration_type';
            }
            if (Schema::hasColumn('residents', 'submitted_at')) {
                $columnsToDrop[] = 'submitted_at';
            }
            if (Schema::hasColumn('residents', 'approved_at')) {
                $columnsToDrop[] = 'approved_at';
            }
            if (Schema::hasColumn('residents', 'rejected_at')) {
                $columnsToDrop[] = 'rejected_at';
            }
            if (Schema::hasColumn('residents', 'approved_by')) {
                $columnsToDrop[] = 'approved_by';
            }
            if (Schema::hasColumn('residents', 'rejected_by')) {
                $columnsToDrop[] = 'rejected_by';
            }
            if (Schema::hasColumn('residents', 'rejection_reason')) {
                $columnsToDrop[] = 'rejection_reason';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Get existing foreign keys for a table
     */
    private function getExistingForeignKeys($table)
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();

        $result = $connection->select(
            "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$databaseName, $table]
        );

        return array_column($result, 'CONSTRAINT_NAME');
    }
};
