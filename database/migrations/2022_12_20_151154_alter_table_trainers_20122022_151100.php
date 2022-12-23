<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTrainers20122022151100 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->unique('trainer_code', 'trainers_unique_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropUnique('trainers_unique_1');
        });
    }
}
