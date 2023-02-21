<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('project_id');
            $table->text('concept');
            $table->double('amount', 8, 2);
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete()()->cascadeOnUpdate();
            $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete()->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_payments');
    }
}
