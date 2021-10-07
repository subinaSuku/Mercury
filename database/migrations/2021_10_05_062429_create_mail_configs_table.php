<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_configs', function (Blueprint $table) {
            $table->id();
            $table->string('mailer');
            $table->string('host' );
            $table->string('port' );
            $table->string('from_address' );
            $table->string('from_name');
            $table->string('encrpytion' );
            $table->string('username' );
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('mail_configs');
    }
}
