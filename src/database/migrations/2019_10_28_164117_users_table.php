<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 30)->after('username')->nullable();
            $table->string('last_name', 30)->after('username')->nullable();
            $table->string('phone', 15)->after('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->after('phone')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropColumn('first_name');
            Schema::dropColumn('last_name');
            Schema::dropColumn('phone');
            Schema::dropColumn('phone_verified_at');
        });
    }
}
