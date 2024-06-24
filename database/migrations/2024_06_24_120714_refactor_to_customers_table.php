<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('application');
            $table->dropColumn('isApplication');
            $table->dropColumn('joinedAt');
            $table->renameColumn('createdBy_id', 'user_id');
            $table->date('joined_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer("application");
            $table->boolean("isApplication");
            $table->date("joinedAt");
            $table->integer("application");
            $table->renameColumn('user_id', 'createdBy_id');
            $table->dropColumn('joined_at');
        });
    }
}
