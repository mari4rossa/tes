<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTrainingHistories21122022091200 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_histories', function (Blueprint $table) {
            $table->string('trainer_code', 8)->default('')->after('trainer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_histories', function (Blueprint $table) {
            $table->dropColumn('trainer_code');
        });
    }
}
