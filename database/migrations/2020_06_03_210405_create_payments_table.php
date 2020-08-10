<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('stripe_id');
            $table->string('stripe_customer_id');
            $table->string('dashboard_url');
            $table->string('stripe_price_id');
            $table->string('product_id');
            $table->boolean('is_active');
            $table->string('name');
            $table->date('birthday');
            $table->string('country');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('gender');
            $table->string('card_number');
            $table->string('exp_mm');
            $table->string('exp_yyyy');
            $table->string('cvv');
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_zip');
            $table->boolean('subscribe_content_confirm');
            $table->boolean('sell_content_confirm');
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
        Schema::dropIfExists('payments');
    }
}
