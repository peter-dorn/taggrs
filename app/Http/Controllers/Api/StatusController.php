<?php

namespace App\Http\Controllers\Api;

use App\Constants\QueueConstants;
use App\Http\Controllers\Controller;
use App\Models\Tracking;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class StatusController extends Controller
{
    public function redis(): JsonResponse
    {
        try {
            $items = Redis::lrange(QueueConstants::TRACKING_QUEUE_KEY, 0, -1);

            $decodedItems = array_map(function ($item) {
                $decoded = json_decode($item, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::warning('Failed to decode queue item', [
                        'item' => $item,
                        'error' => json_last_error_msg()
                    ]);
                    return null;
                }
                return $decoded;
            }, $items);

            // Filter out any failed decodes
            $decodedItems = array_filter($decodedItems);

            return response()->json([
                'status' => 'online',
                'data' => $decodedItems,
                'count' => count($decodedItems)
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch queue items', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'offline',
                'data' => [],
                'count' => 0
            ]);
        }
    }

    public function database()
    {
        try {
            DB::connection()->getPdo();

            $trackingCount = Tracking::count();

            return response()->json([
                'status' => 'online',
                'count' => $trackingCount
            ]);
        } catch (\Exception $e) {
            Log::error('Database connection failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'offline',
                'count' => 0
            ]);
        }

    }
}
