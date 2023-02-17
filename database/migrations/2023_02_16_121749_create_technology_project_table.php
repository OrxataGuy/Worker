<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnologyProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technology_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('technology_id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('technology_id')->references('id')->on('technologies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technology_project');
    }
}
