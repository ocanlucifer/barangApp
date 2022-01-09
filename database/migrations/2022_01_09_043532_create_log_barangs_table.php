<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->dropIfExists('log_barangs');
        Schema::connection('pgsql')->create('log_barangs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_barang');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->float('qty_awal');
            $table->float('qty_masuk');
            $table->float('qty_keluar');
            $table->float('qty_akhir');
            $table->longText('aktifitas');
            $table->string('satuan',50);
            $table->integer('user_id');
            $table->string('username',50);
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
        Schema::connection('pgsql')->dropIfExists('log_barangs');
    }
}
