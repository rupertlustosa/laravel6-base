<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sale')->nullable();
            $table->dateTime('date');
            $table->float('value')->nullable();
            $table->string('fuel_pump')->nullable();
            $table->string('fuel_pump_nozzle')->nullable();
            $table->string('attendant')->nullable();
            $table->string('client')->nullable();
            $table->string('item_identification')->nullable();
            $table->string('item_name')->nullable();
            $table->float('item_quantity')->nullable();
            $table->float('item_unit_price')->nullable();;
            $table->dateTime('synced_at')->nullable()->index('sales_synced_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sales');
    }

}
