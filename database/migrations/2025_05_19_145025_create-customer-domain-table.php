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
        Schema::create('customer_domain', function (Blueprint $table) {
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('domain_id')->constrained('domains')->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['domain_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_domain');
    }
};
