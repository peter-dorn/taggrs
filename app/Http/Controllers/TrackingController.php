<?php

namespace App\Http\Controllers;

use App\Constants\QueueConstants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TrackingController extends Controller
{
    public function home(Request $request)
    {
        echo 'Hi';
    }

    /**
     * Handle the tracking request
     */
    public function track(Request $request): JsonResponse
    {
        $trackingData = $request->validate([
            'event' => 'required|string',
            'data' => 'required|array'
        ]);

        $trackingData['timestamp'] = now()->toIso8601String();
        $key = 'tracking:' . uniqid();

        Redis::rpush(QueueConstants::TRACKING_QUEUE_KEY, json_encode($trackingData));

        return response()->json([
            'status' => 'success',
            'message' => 'Tracking data received',
            'tracking_id' => $key
        ]);
    }

}
