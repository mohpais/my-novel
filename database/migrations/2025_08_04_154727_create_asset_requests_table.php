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
        Schema::create('asset_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code', 20)->unique()->nullable(); // Nomor aset unik (biasanya mengikuti format internal perusahaan, seperti kode inventaris).
            
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('request_status_id')->nullable()->constrained('request_statuses')->cascadeOnDelete();

            $table->integer('purchase_quantity');
            $table->string('name', 300); // Nama aset (misal: “Laptop Dell Latitude 5440”).
            $table->string('description', 3000)->nullable();
            $table->string('reason', 10000)->nullable(); // Alasan kenapa aset ini ada (dari request → kenapa dibeli/diganti).
            $table->boolean('replacement')->nullable()->default(false);
            $table->integer('useful_life')->nullable(); // Umur manfaat aset dalam tahun
            $table->integer('initial_value')->nullable(); // Nilai perolehan awal aset (harga beli). Biasanya akan diisi ketika Vendor dipilih
            $table->string('currency', 3)->nullable(); // Mata uang pembelian (misal: IDR, USD).

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_requests');
    }
};
