<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('gender');
            $table->integer('exp')->default(0);
            $table->integer('berat');
            $table->integer('tinggi');
            $table->json('inventory')->nullable();
            $table->json('setting')->nullable();
            $table->boolean('is_verify')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
