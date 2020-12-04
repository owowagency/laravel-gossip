<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Because of the order of how the migrations are being ran when testing
        // this package the users table is created later then the messages
        // table. This creates a foreign key constraint. That's why we added the
        // migration here. This migration can be deleted in your application.
        if (app()->environment('testing')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (app()->environment('testing')) {
            Schema::dropIfExists('users');
        }
    }
}
