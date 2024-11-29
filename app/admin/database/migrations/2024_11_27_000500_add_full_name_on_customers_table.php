<?php

namespace Admin\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullNameOnCustomersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('customers', 'full_name'))
            return;

        Schema::table('customers', function (Blueprint $table) {
            $table->text('full_name')->nullable()->after('last_name');
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
