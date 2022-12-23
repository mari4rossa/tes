<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTrainers20122022145400 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->string('trainer_code', 8)->default('')->after('id');
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
            $table->dropColumn('trainer_code');
        });
    }
}
