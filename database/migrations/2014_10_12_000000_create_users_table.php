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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('restricted_access')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        $admin = new \App\User;
        $admin->name = 'Lippo Administrator';
        $admin->email = 'info@webarq.com';
        $admin->restricted_access = 1;
        $admin->password = \Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'webarq'));
        $admin->save();
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
