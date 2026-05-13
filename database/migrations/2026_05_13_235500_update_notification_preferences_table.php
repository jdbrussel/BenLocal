<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->boolean('business_claim_updates')->default(true)->after('spot_updates');
            $table->boolean('owner_responses')->default(true)->after('business_claim_updates');
            $table->boolean('campaign_selections')->default(true)->after('owner_responses');
        });
    }

    public function down(): void
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->dropColumn(['business_claim_updates', 'owner_responses', 'campaign_selections']);
        });
    }
};
