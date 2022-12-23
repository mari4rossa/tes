<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('nik', 8)->default('');
            $table->string('name', 50)->default('');
            $table->string('email', 30)->default('');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->string('position_name', 30)->default('');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name', 30)->default('');
            $table->unsignedBigInteger('training_id')->nullable();
            $table->string('training_name', 180)->default('');
            $table->unsignedBigInteger('trainer_id')->nullable();
            $table->string('trainer_name', 50)->default('');
            $table->string('trainer_email', 30)->default('');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('training_histories');
    }
}
