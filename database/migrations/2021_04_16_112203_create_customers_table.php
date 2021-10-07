<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {           
            $table->id();
            $table->string('first_name', 20);
            $table->string('last_name', 20)->nullable();
            $table->unsignedBigInteger('customer_no');
            $table->string('std_code', 5)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 60)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('user_id');
            $table->rememberToken();
            $table->timestamps();
			$table->boolean('is_deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
