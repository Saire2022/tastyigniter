<?php

namespace Admin\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomersTableAddVarcharToFullName extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('full_name', 128)->nullable()->after('last_name'); // Define varchar(128)
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('customers', 'full_name'))
            return;

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
}
