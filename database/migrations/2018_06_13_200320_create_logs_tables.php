<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Logs;

class CreateLogsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Logs::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity', false);
            $table->float('unit_price');
            $table->float('total_price')->nullable();
            // For a log, we can't have a foreign key, as we want the log survive to the product related
            // If not, when we delete the product, all stock transaction are gone
            $table->integer('product_id');
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
        Schema::dropIfExists(Logs::TABLE_NAME);
    }
}
