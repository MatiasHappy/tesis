<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('repeat_interval'); // number of days for the repeat interval
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Optional end date
            $table->integer('duration')->nullable(); // Optional duration in minutes
            $table->enum('time_of_day', ['morning', 'afternoon', 'evening', 'night']); // Time of day
            $table->unsignedBigInteger('task_category_id'); // Foreign key column
            $table->foreign('task_category_id')->references('id')->on('task_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
