<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoormanGroupPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(config('doorman.uses_groups')) {
            Schema::create('group_permission', function (Blueprint $table) {
                $table->unsignedInteger('group_id');
                $table->unsignedInteger('permission_id');
                $table->primary(['group_id', 'permission_id']);
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
        Schema::dropIfExists('group_permission');
    }
}
