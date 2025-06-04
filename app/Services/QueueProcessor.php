<?php

namespace App\Services;

use App\Constants\QueueConstants;
use App\Models\Tracking;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class QueueProcessor
{
    private const BATCH_SIZE = 3;

    public function processBatch(): array
    {
        $processedItems = [];

        try {
            $items = $this->fetchItemsFromQueue();

            foreach ($items as $item) {
                $decodedItem = json_decode($item, true);
                if (!$decodedItem) {
                    Log::warning('Invalid JSON format in queue item', ['item' => $item]);
                    continue;
                }

                try {
                    $processedItem = $this->processItem($decodedItem);
                    $processedItems[] = $processedItem;

                    Log::info('Item processed successfully', [
                        'item' => $decodedItem,
                        'processed' => $processedItem
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to process item', [
                        'item' => $decodedItem,
                        'error' => $e->getMessage()
                    ]);

                    $this->returnItemToQueue($item);
                }
            }
        } catch (\Exception $e) {
            Log::error('Queue processing failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $processedItems;
    }

    private function fetchItemsFromQueue(): array
    {
        return Redis::transaction(function ($redis) {
            $batch = [];
            for ($i = 0; $i < self::BATCH_SIZE; $i++) {
                $item = $redis->lpop(QueueConstants::TRACKING_QUEUE_KEY);
                if ($item) {
                    $batch[] = $item;
                }
            }
            return $batch;
        });
    }

    private function returnItemToQueue(string $item): void
    {
        Redis::rpush(QueueConstants::TRACKING_QUEUE_KEY, $item);
    }

    private function processItem(array $data): array
    {
        Tracking::create([
            'event' => $data['event'],
            'timestamp' => now(),
        ]);

        return [
            'original_data' => $data,
            'processed_at' => now()->toIsoString(),
            'status' => 'completed'
        ];
    }
}
