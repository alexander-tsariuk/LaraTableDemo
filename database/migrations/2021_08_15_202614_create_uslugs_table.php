<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uslugs', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
            $table->id();
            $table->string('name')->comment('Наименование');
            $table->string('vendor_code')->comment('Артикул');
            $table->string('barcode')
                ->comment('Штрихкод')
                ->nullable(1);
            $table->unsignedBigInteger('unit_id')
                ->nullable(1)
                ->comment('Единица измерения');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->nullOnDelete();
            $table->decimal('buy_price', 15, 5)
                ->default(0)
                ->comment('Зак. цена');
            $table->char('buy_currency', 3)->comment('Валюта Зак.');
            $table->decimal('sale_price', 15, 5)
                ->default(0)
                ->comment('Прод. цена');
            $table->char('sale_currency', 3)->comment('Валюта Прод.');
            $table->uuid('uuid')->comment('Уникальный идентификатора на всякий случай');
            $table->comment('Услуги');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uslugs');
    }
};
