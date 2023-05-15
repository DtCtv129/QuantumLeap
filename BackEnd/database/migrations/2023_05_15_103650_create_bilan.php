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
        Schema::create('bilan', function (Blueprint $table) {
            $table->id();
            $table->decimal('budget_actuel', 8, 2);
            $table->string('titre_programme');
            $table->decimal('transaction_value', 8, 2);
            $table->decimal('budget_apres_trans', 8, 2);
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilan');
    }
};
