<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->unique()->after('pesanan_id');
            $table->string('snap_token')->nullable()->after('bukti_pembayaran');
            $table->text('snap_redirect_url')->nullable()->after('snap_token');
            $table->string('transaction_id')->nullable()->after('snap_redirect_url');
            $table->string('payment_type')->nullable()->after('transaction_id');
            $table->string('fraud_status')->nullable()->after('payment_type');
            $table->json('midtrans_response')->nullable()->after('fraud_status');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropUnique(['midtrans_order_id']);

            $table->dropColumn([
                'midtrans_order_id',
                'snap_token',
                'snap_redirect_url',
                'transaction_id',
                'payment_type',
                'fraud_status',
                'midtrans_response',
            ]);
        });
    }
};