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
            $table->string('name',24)->nullable()->index();//名字
            $table->string('phone',15)->unique()->nullable()->index();//手机号码
            $table->string('wx_id')->unique()->nullable()->index();//微信id
            $table->string('email',40)->unique()->nullable()->index();//邮箱
            $table->boolean('email_active')->default(false);
            $table->string('type',20)->nullable();//类型
            $table->string('avatar')->nullable();
            $table->boolean('active')->default(false);
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
