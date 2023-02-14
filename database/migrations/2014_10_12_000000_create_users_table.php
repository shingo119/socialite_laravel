<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('github_id')->nullable();
            $table->bigInteger('passport_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable(); // 追記
            $table->string('password')->nullable(); // 追記
            $table->string('github_token')->nullable();
            $table->string('github_refresh_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
