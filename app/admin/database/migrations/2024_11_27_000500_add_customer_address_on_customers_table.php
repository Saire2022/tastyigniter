<?php

namespace Admin\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerAddressOnCustomersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('customers', 'customer_address'))
            return;

        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_address', 100)->nullable()->after('identification');
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('customers', 'customer_address'))
            return;

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('customer_address');
        });
    }
}
