<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'wallet_balance')) {
                $table->decimal('wallet_balance', 12, 2)->default(0)->after('is_active');
            }

            if (!Schema::hasColumn('users', 'outstanding_debt')) {
                $table->decimal('outstanding_debt', 12, 2)->default(0)->after('wallet_balance');
            }
        });

        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'source_type')) {
                $table->string('source_type')->default('purchase')->after('rating');
            }

            if (!Schema::hasColumn('books', 'source_name')) {
                $table->string('source_name')->nullable()->after('source_type');
            }

            if (!Schema::hasColumn('books', 'source_url')) {
                $table->string('source_url')->nullable()->after('source_name');
            }

            if (!Schema::hasColumn('books', 'borrow_fee')) {
                $table->integer('borrow_fee')->default(0)->after('source_url');
            }

            if (!Schema::hasColumn('books', 'daily_late_fee')) {
                $table->integer('daily_late_fee')->default(5000)->after('borrow_fee');
            }

            if (!Schema::hasColumn('books', 'is_digital')) {
                $table->boolean('is_digital')->default(false)->after('daily_late_fee');
            }

            if (!Schema::hasColumn('books', 'file_path')) {
                $table->string('file_path')->nullable()->after('is_digital');
            }
        });

        Schema::table('borrows', function (Blueprint $table) {
            if (!Schema::hasColumn('borrows', 'borrow_fee')) {
                $table->integer('borrow_fee')->default(0)->after('fine_amount');
            }

            if (!Schema::hasColumn('borrows', 'late_fee')) {
                $table->integer('late_fee')->default(0)->after('borrow_fee');
            }

            if (!Schema::hasColumn('borrows', 'late_fee_collected')) {
                $table->integer('late_fee_collected')->default(0)->after('late_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $toDrop = [];

            foreach (['borrow_fee', 'late_fee', 'late_fee_collected'] as $column) {
                if (Schema::hasColumn('borrows', $column)) {
                    $toDrop[] = $column;
                }
            }

            if (!empty($toDrop)) {
                $table->dropColumn($toDrop);
            }
        });

        Schema::table('books', function (Blueprint $table) {
            $toDrop = [];

            foreach (['source_type', 'source_name', 'source_url', 'borrow_fee', 'daily_late_fee', 'is_digital', 'file_path'] as $column) {
                if (Schema::hasColumn('books', $column)) {
                    $toDrop[] = $column;
                }
            }

            if (!empty($toDrop)) {
                $table->dropColumn($toDrop);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $toDrop = [];

            foreach (['wallet_balance', 'outstanding_debt'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $toDrop[] = $column;
                }
            }

            if (!empty($toDrop)) {
                $table->dropColumn($toDrop);
            }
        });
    }
};
