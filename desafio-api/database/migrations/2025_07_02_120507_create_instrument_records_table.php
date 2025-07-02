<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instrument_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_file_id')->constrained('uploaded_files')->onDelete('cascade');
            $table->date('RptDt');
            $table->string('TckrSymb');
            $table->string('MktNm')->nullable();
            $table->string('SctyCtgyNm')->nullable();
            $table->string('ISIN')->nullable();
            $table->string('CrpnNm')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrument_records');
    }
};