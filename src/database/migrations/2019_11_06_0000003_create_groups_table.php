<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(config('doorman.uses_groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->timestamps();
            });

            Schema::create('group_permission', function (Blueprint $table) {
                $table->unsignedInteger('group_id');
                $table->unsignedInteger('permission_id');
                $table->primary(['group_id', 'permission_id']);
            });

            Schema::create('group_user', function (Blueprint $table) {
                $table->unsignedInteger('group_id');
                $table->unsignedInteger('user_id');
                $table->boolean('is_current')->default(false);
                $table->primary(['group_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
        Schema::dropIfExists('group_permission');
        Schema::dropIfExists('group_user');
    }
}
