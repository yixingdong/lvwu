<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64);//名字
            $table->string('phone',15)->unique()->index();//手机号码
            $table->string('wx_id')->unique()->index();//微信id
            $table->string('email')->unique()->index();//邮箱
            $table->string('type',20);//类型
            $table->string('avatar');
            $table->boolean('active')->default(0);
            $table->string('password', 60);//密码
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
        Schema::drop('users');
    }
}
