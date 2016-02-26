<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUSSDsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ussd_registration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname');
            $table->string('phonenumber');
            $table->string('electoral_ward');
            $table->integer('national_id');
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
        Schema::drop('ussd_registration');
    }
}
