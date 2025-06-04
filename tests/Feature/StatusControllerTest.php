<?php

namespace Feature;

use App\Constants\QueueConstants;
use App\Models\Tracking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StatusControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test] public function database_returns_online_status_when_available()
    {
        // Arrange
        Tracking::factory()->count(5)->create();

        // Act
        $response = $this->getJson('/api/status/database');

        // Assert
        $response->assertOk()
            ->assertJson([
                'status' => 'online',
                'count' => 5
            ]);
    }

    #[Test] public function database_returns_zero_tracking_count_when_no_records_exist()
    {
        // Act
        $response = $this->getJson('/api/status/database');

        // Assert
        $response->assertOk()
            ->assertJson([
                'status' => 'online',
                'count' => 0
            ]);
    }

    #[Test] public function database_returns_offline_status_when_connection_fails()
    {
        // Arrange
        DB::shouldReceive('connection->getPdo')
            ->once()
            ->andThrow(new \Exception('Connection failed'));

        // Act
        $response = $this->getJson('/api/status/database');

        // Assert
        $response->assertOk()
            ->assertJson([
                'status' => 'offline',
                'count' => 0
            ]);
    }

    #[Test] public function redis_returns_online_status_when_available()
    {
        // Arrange
        $mockConnection = \Mockery::mock('connection');
        Redis::shouldReceive('lrange')
            ->once()
            ->with(QueueConstants::TRACKING_QUEUE_KEY, 0, -1)
            ->andReturn(['{"key":"value"}']);

        // Act
        $response = $this->getJson('/api/status/redis');

        // Assert
        $response->assertOk()
            ->assertJson([
                'status' => 'online'
            ]);
    }

    #[Test] public function redis_returns_offline_status_when_connection_fails()
    {
        // Arrange
        Redis::shouldReceive('lrange')
            ->once()
            ->with(QueueConstants::TRACKING_QUEUE_KEY, 0, -1)
            ->andThrow(new \Exception('Connection failed'));

        // Act
        $response = $this->getJson('/api/status/redis');

        // Assert
        $response->assertOk()
            ->assertJson([
                'status' => 'offline'
            ]);
    }
}
