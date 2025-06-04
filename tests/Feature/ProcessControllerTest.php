<?php

namespace Feature;

use App\Services\QueueProcessor;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProcessControllerTest extends TestCase
{
    #[Test] public function process_returns_successful_response_with_processed_items()
    {
        // Arrange
        $mockProcessedItems = [
            ['id' => 1, 'status' => 'processed'],
            ['id' => 2, 'status' => 'processed']
        ];

        $mockQueueProcessor = Mockery::mock(QueueProcessor::class);
        $mockQueueProcessor->shouldReceive('processBatch')
            ->once()
            ->andReturn($mockProcessedItems);

        $this->app->instance(QueueProcessor::class, $mockQueueProcessor);

        // Act
        $response = $this->postJson('/api/process');

        // Assert
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'processed_count' => 2,
                'processed_items' => $mockProcessedItems
            ]);
    }

    #[Test] public function process_returns_successful_response_with_empty_items()
    {
        // Arrange
        $mockQueueProcessor = Mockery::mock(QueueProcessor::class);
        $mockQueueProcessor->shouldReceive('processBatch')
            ->once()
            ->andReturn([]);

        $this->app->instance(QueueProcessor::class, $mockQueueProcessor);

        // Act
        $response = $this->postJson('/api/process');

        // Assert
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'processed_count' => 0,
                'processed_items' => []
            ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

}
