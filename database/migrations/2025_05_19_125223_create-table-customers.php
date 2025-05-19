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
        Schema::create(
            'customers',
            function( Blueprint $table ) {
                $table->uuid( 'id' )->primary()->unique();
                $table->foreignUuid( 'user_id' )->nullable()->onDelete( 'set null' );
                $table->foreignUuid( 'support_id' )->nullable()->onDelete( 'set null' );
                $table->string( 'title' );
                $table->string( 'display_name' )->nullable();
                $table->string( 'image' )->nullable();
                $table->text( 'description' )->nullable();
                $table->uuid('calendar_uuid');
                $table->uuid('messenger_manager_uuid');
                $table->string('economic_code');
                $table->string('phone');
                $table->string('mobile');
                $table->string('email');
                $table->string('national_code');
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists( 'customers');
    }
};
