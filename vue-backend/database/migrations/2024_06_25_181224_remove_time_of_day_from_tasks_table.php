<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTimeOfDayFromTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('time_of_day');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->json('time_of_day')->nullable();
        });
    }
}
