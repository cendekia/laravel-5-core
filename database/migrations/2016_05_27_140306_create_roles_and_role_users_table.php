<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesAndRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->integer('parent_role_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('permissions')->nullable();
            $table->text('whitelisted_ip_addresses')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->engine = 'InnoDB';
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('registered_ip_address')->nullable();
            $table->nullableTimestamps();

            $table->engine = 'InnoDB';
            $table->primary(['user_id', 'role_id']);
        });

        // Add super admin role and assign it to default user
        $role = new \App\Models\Role;
        $role->slug = 'super-admin';
        $role->name = 'Super Admin';

        if ($role->save()) {
            $user = \App\User::find(1);

            $user->roles()->attach($role->id);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
        Schema::drop('roles');
    }
}
