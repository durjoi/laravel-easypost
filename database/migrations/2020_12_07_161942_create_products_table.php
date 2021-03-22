<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id');
            $table->string('model');
            $table->string('sku')->nullable();
            $table->string('excellent_offer')->nullable();
            $table->string('good_offer')->nullable();
            $table->string('fair_offer')->nullable();
            $table->string('poor_offer')->nullable();
            $table->string('amount')->nullable();
            $table->string('storage')->nullable();
            $table->text('description')->nullable();
            $table->text('color')->nullable();
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('weight')->nullable();
            $table->string('length')->nullable();
            $table->string('device_type');
            $table->string('network')->nullable();
            $table->integer('offer_type')->default(0)->nullable();
            $table->string('status')->default('Active')->nullable();
            $table->integer('user_create');
            $table->integer('user_update');
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
        Schema::dropIfExists('products');
    }
}
