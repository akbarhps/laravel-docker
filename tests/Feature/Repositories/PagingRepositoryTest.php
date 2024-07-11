<?php

namespace Repositories;

use App\Models\User;
use App\Repositories\Repository;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PagingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private readonly Repository $repository;
    private const TEST_ROUTE_ENDPOINT = "/test_paging_route";
    private const ITEMS_PER_PAGE = 15;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);

        // register custom route for testing purpose
        Route::get(self::TEST_ROUTE_ENDPOINT, function () {
            $cursor = $this->repository->findAllWithCursor(limitPerPage: self::ITEMS_PER_PAGE);
            return response()->json($cursor);
        });
    }

    public function test_findAllWithCursor_empty_table_should_return_empty_data()
    {
        $response = $this->getJson(self::TEST_ROUTE_ENDPOINT);
        $this->assertEmpty($response->json("data"));
    }

    public function test_findAllWithCursor_with_data_should_return_data()
    {
        User::factory()->count(20)->create();

        $response = $this->getJson(self::TEST_ROUTE_ENDPOINT);
        $this->assertNotEmpty($response->json("data"));
        $this->assertNotNull($response->json("next_cursor"));
        $this->assertCount(self::ITEMS_PER_PAGE, $response->json("data"));
    }

    public function test_findAllWithCursor_should_return_data_when_querying_next_page()
    {
        User::factory()->count(20)->create();

        $response = $this->getJson(self::TEST_ROUTE_ENDPOINT);
        $this->assertNotEmpty($response->json("data"));
        $this->assertNotNull($response->json("next_cursor"));
        $this->assertCount(self::ITEMS_PER_PAGE, $response->json("data"));

        $response = $this->getJson($response->json("next_page_url"));
        $this->assertNotEmpty($response->json("data"));
        $this->assertNull($response->json("next_cursor"));
        $this->assertCount(20 - self::ITEMS_PER_PAGE, $response->json("data"));
    }

}
