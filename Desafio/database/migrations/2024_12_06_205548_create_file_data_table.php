<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileDataTable extends Migration
{
    public function up()
    {
        Schema::create('file_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained('uploads')->onDelete('cascade');
            $table->date('RptDt');
            $table->string('TckrSymb');
            $table->string('MktNm');
            $table->string('SctyCtgyNm');
            $table->string('ISIN');
            $table->string('CrpnNm');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_data');
    }
}