<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnulmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annulments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->char('state_type_id', 2);
            $table->char('soap_type_id', 2);
            $table->date('date_of_issue');
            $table->date('date_of_reference');
            $table->string('identifier');
            $table->string('filename');
            $table->string('ticket')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('state_type_id')->references('id')->on('state_types');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annulments');
    }
}
