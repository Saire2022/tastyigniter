<?php

namespace Admin\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullNameOnOrdersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('orders', 'full_name'))
            return;

        Schema::table('orders', function (Blueprint $table) {
            $table->string('full_name', 128)->nullable()->after('last_name'); // Define varchar(128)
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('orders', 'full_name'))
            return;

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
}
