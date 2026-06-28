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
        Schema::table('election_schedules', function (Blueprint $table) {
            $table->foreignId('election_period_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('election_periods')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('election_schedules', function (Blueprint $table) {
            $table->dropForeign(['election_period_id']);
            $table->dropColumn('election_period_id');
        });
    }
};
