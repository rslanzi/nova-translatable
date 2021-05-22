<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('test_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained();
            $table->string('locale')->index();
            $table->string('title');
            $table->string('slug');

            $table->unique(['test_id', 'locale']);
        });
    }
}
