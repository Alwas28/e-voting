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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_period_id')->nullable()->constrained('election_periods')->nullOnDelete();
            $table->unsignedTinyInteger('number');           // nomor urut
            $table->string('name', 150);
            $table->string('photo', 255)->nullable();        // path file foto
            $table->text('vision')->nullable();              // visi
            $table->text('mission')->nullable();             // misi
            $table->string('faculty', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['election_period_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
