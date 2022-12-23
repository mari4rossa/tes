<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('nik', 8)->default('');
            $table->string('name', 50)->default('');
            $table->string('email', 30)->default('');
            $table->string('old_position', 30)->default('');
            $table->string('new_position', 30)->default('');
            $table->string('old_department', 30)->default('');
            $table->string('new_department', 30)->default('');
            $table->date('start_date')->nullable();
            $table->dateTime('created_at')->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutations');
    }
}
