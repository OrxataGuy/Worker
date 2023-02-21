<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('parent_task_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('details')->nullable();
            $table->double('price', 8, 2)->default(0);
            $table->double('time', 8, 0)->default(0);
            $table->integer('mins')->default(0);
            $table->integer('secs')->default(0);
            $table->timestamp('last_run')->nullable();
            $table->tinyInteger('counting')->default(0);
            $table->tinyInteger('bug')->default(0);
            $table->tinyInteger('finished')->default(0);
            $table->tinyInteger('paid')->default(0);
            $table->text('solution')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('parent_task_id')->references('id')->on('tasks')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
