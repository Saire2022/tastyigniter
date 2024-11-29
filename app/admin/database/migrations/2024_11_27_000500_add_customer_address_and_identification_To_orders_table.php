<?php

namespace Admin\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerAddressAndIdentificationToOrdersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('orders', 'customer_address') && Schema::hasColumn('orders', 'identification'))
            return;

        Schema::table('orders', function (Blueprint $table) {
            $table->string('identification', 10)->nullable()->after('full_name');
            $table->string('customer_address', 100)->nullable()->after('identification');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_address', 'identification']);
        });
    }
}
