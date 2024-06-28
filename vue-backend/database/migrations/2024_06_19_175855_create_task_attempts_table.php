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
        Schema::create('task_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id'); // Foreign key to tasks table
            $table->date('attempt_date'); // Date of the attempt
            $table->enum('status', ['completed', 'failed']); // Status of the attempt
            $table->timestamps(); // Created at and Updated at columns

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('task_attempts');
    }
};
