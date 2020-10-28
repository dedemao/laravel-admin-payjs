<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayjsConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payjs_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mchid', 30)->comment('商户号');
            $table->string('appkey', 100)->comment('通信密钥');
            $table->string('pay_channel', 10)->default('all')->comment('支付通道');
            $table->string('notify_url', 100)->comment('回调通知');
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
        Schema::dropIfExists('payjs_configs');
    }
}
