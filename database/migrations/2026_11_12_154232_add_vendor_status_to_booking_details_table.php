<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_details', function (Blueprint $table) {
            $table->enum('vendor_status', [
                'pending',
                'confirmed',
                'rejected',
                'ongoing',
                'completed'
            ])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('booking_details', function (Blueprint $table) {
            $table->dropColumn('vendor_status');
        });
    }
};