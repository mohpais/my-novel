<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_request_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_request_id')->constrained('asset_requests')->cascadeOnDelete();
            $table->integer('purchase_quantity'); // Jumlah unit barang yang akan dibeli sesuai vendor ini. Bisa berbeda dengan quantity di request (misalnya vendor hanya bisa supply sebagian)
            $table->string('vendor_name');
            $table->string('currency'); // Mata uang quotation (misalnya USD, IDR)
            $table->float('base_rate'); // Harga satuan barang sebelum pajak (misalnya 10,000/unit).
            $table->float('amount'); // base_rate * purchase_quantity. Nilai total sebelum pajak
            $table->float('tax_rate'); // Persentase pajak yang berlaku (misalnya 11% untuk PPN).
            $table->float('vat_amount'); // Jumlah pajak (amount * tax_rate).
            $table->float('total'); // Nilai akhir (amount + vat_amount). Ini yang dipakai untuk evaluasi biaya. dan diisi ke initial_value
            $table->boolean('luxury_goods')->default(false); // True kalau barang termasuk barang mewah (kena pajak tambahan atau perlu approval level lebih tinggi).

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // User yang membuat peran ini
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // User yang terakhir mengupdate peran ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_request_quotations');
    }
};
