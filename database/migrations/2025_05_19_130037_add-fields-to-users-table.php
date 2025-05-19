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
        Schema::table(
            'users',
            function( Blueprint $table ) {
                $table->dropColumn('id');
                $table->uuid( 'id' )->primary()->unique();
                $table->foreignUuid( 'user_id' )->nullable()->onDelete( 'set null' );
                $table->foreignUuid( 'customer_id' )->nullable()->onDelete( 'set null' );
                $table->string( 'first_name' )->nullable();
                $table->string( 'last_name' )->nullable();
                $table->text( 'bio' )->nullable();
                $table->string( 'job' )->nullable();
                $table->enum( 'status', [ 'pending', 'active', 'inactive', 'expired' ] )->default( 'pending' );
                $table->dateTime( 'expired_at' )->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('user_id');
            $table->dropColumn('user_id');
            $table->dropColumn('first_name');
            $table->dropColumn( 'last_name' );
            $table->dropColumn( 'bio' );
            $table->dropColumn( 'job' );
            $table->dropColumn( 'status' );
            $table->dropColumn( 'expired_at' );
        });
    }
};
