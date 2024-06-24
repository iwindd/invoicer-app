<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorRoleToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('application');
            $table->dropColumn('owner');
            $table->dropColumn('root');
            $table->dropColumn('permission');
            $table->enum('role', [ 'user', 'application'])->default('user');
            $table->unsignedBigInteger('customer_id')->nullable(); 
            $table->foreign('customer_id')->references('id')->on('customers');
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
            $table->integer("application");
            $table->boolean('owner');
            $table->boolean('root');
            $table->integer('permission');
            $table->dropColumn('role');
            $table->dropColumn('customer_id');
        });
    }
}
