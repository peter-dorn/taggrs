<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\QueueProcessor;
use Illuminate\Http\JsonResponse;

class ProcessController extends Controller
{
    public function __construct(
        private readonly QueueProcessor $queueProcessor
    ) {}

    public function process(): JsonResponse
    {
        $processedItems = $this->queueProcessor->processBatch();

        return response()->json([
            'success' => true,
            'processed_count' => count($processedItems),
            'processed_items' => $processedItems
        ]);
    }
}
