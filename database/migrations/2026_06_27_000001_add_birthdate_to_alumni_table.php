<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->string('place_of_birth')->nullable()->after('department');
            $table->date('date_of_birth')->nullable()->after('place_of_birth');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('alumni_id')->nullable()->constrained('alumni')->nullOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropConstrainedForeignId('alumni_id'));
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn(['place_of_birth', 'date_of_birth']);
        });
    }
};
