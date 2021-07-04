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
            $table->string('slug')->unique();
            $table->decimal('price',18,4)->unsigned();//بدون اشارة
            $table->decimal('special_price',18,4)->unsigned()->nullable();
            $table->string('special_price_type')->nullable();
            $table->date('special_price_start')->nullable();//يبدأ بتاريخ
            $table->date('special_price_end')->nullable();//ينتهي بتاريخ
            $table->decimal('selling_price',18,4)->unsigned()->nullable();//سعر البيع
            $table->string('sku')->nullable();//التحقق الكود
            $table->boolean('manage_stock');//ادارة المخازن
            $table->integer('qty')->nullable();//الكمية
            $table->boolean('in_stock');//متوفر في المخزن
            $table->integer('viewed')->unsigned()->default(0);//عدد المشاهدين
            $table->boolean('is_active');
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->softDeletes();
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
