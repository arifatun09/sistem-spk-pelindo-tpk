<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alat_master_id');
            $table->string('periode');
            $table->decimal('utilisasi', 5, 2);
            $table->decimal('availability', 5, 2);
            $table->decimal('reliability', 5, 2);
            $table->decimal('idle', 5, 2);
            $table->decimal('jam_tersedia', 5, 2);
            $table->decimal('jam_operasi', 5, 2);
            $table->decimal('jam_bda', 5, 2);
            $table->decimal('jumlah_bda', 5, 2);
            $table->timestamps();

            $table->foreign('alat_master_id')->references('id')->on('alat_masters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alat');
    }
}
