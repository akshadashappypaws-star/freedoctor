<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Add new time columns
            $table->time('start_time')->nullable()->after('end_date');
            $table->time('end_time')->nullable()->after('start_time');
        });

        // Migrate existing timing data if any exists
        $campaigns = DB::table('campaigns')->get();
        foreach ($campaigns as $campaign) {
            if ($campaign->timings) {
                // Try to parse existing timing format
                // Assuming format might be "09:00 AM - 05:00 PM" or similar
                $timings = $campaign->timings;
                
                // Extract start and end times - basic parsing
                if (strpos($timings, '-') !== false) {
                    $parts = explode('-', $timings);
                    if (count($parts) >= 2) {
                        $startTime = trim($parts[0]);
                        $endTime = trim($parts[1]);
                        
                        // Convert to 24-hour format if needed
                        try {
                            $startTime24 = date('H:i:s', strtotime($startTime));
                            $endTime24 = date('H:i:s', strtotime($endTime));
                            
                            DB::table('campaigns')
                                ->where('id', $campaign->id)
                                ->update([
                                    'start_time' => $startTime24,
                                    'end_time' => $endTime24
                                ]);
                        } catch (Exception $e) {
                            // Set default times if parsing fails
                            DB::table('campaigns')
                                ->where('id', $campaign->id)
                                ->update([
                                    'start_time' => '09:00:00',
                                    'end_time' => '17:00:00'
                                ]);
                        }
                    }
                } else {
                    // Set default times if no dash separator
                    DB::table('campaigns')
                        ->where('id', $campaign->id)
                        ->update([
                            'start_time' => '09:00:00',
                            'end_time' => '17:00:00'
                        ]);
                }
            }
        }

        // Drop the old timings column
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('timings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Add back the timings column
            $table->string('timings')->nullable()->after('end_date');
        });

        // Migrate the time data back to timings format
        $campaigns = DB::table('campaigns')->get();
        foreach ($campaigns as $campaign) {
            if ($campaign->start_time && $campaign->end_time) {
                $startTime = date('h:i A', strtotime($campaign->start_time));
                $endTime = date('h:i A', strtotime($campaign->end_time));
                $timings = "$startTime - $endTime";
                
                DB::table('campaigns')
                    ->where('id', $campaign->id)
                    ->update(['timings' => $timings]);
            }
        }

        // Drop the new time columns
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
