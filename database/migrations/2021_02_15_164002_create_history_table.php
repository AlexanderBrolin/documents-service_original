<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('field_id')->nullable();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('individual_id')->nullable();

            $table->text('before')->nullable();
            $table->text('after')->nullable();
            $table->timestamps();

            $table
                ->foreign('author_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table
                ->foreign('field_id')
                ->references('id')
                ->on('fields')
                ->onDelete('cascade');

            $table
                ->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');

            $table
                ->foreign('individual_id')
                ->references('id')
                ->on('individuals')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history');
    }
}
