<?php

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Repositories\Repository;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CrudRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private readonly Repository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    public function test_findAll_with_empty_table_should_return_empty_array(): void
    {
        $models = $this->repository->findAll();
        $this->assertEmpty($models);
    }

    public function test_findAll_with_filled_table_should_return_array(): void
    {
        User::factory()->count(5)->create();
        $models = $this->repository->findAll();

        $this->assertNotEmpty($models);
        $this->assertCount(5, $models);
    }

    public function test_findById_with_empty_table_should_return_null(): void
    {
        $model = $this->repository->findById(primaryKey: Str::ulid());
        $this->assertNull($model);
    }

    public function test_findById_with_exists_data_should_return_model(): void
    {
        $entity = User::factory()->create();
        $model = $this->repository->findById(primaryKey: $entity->id);

        $this->assertNotNull($model);
        $this->assertEquals($entity->id, $model->id);
    }

    public function test_findById_with_softDeleted_data_should_return_model(): void
    {
        $entity = User::factory()->create();
        $entity->delete();
        $this->assertSoftDeleted('users', ['id' => $entity->id]);

        $model = $this->repository->findById(primaryKey: $entity->id, withTrashed: true);
        $this->assertNotNull($model);
        $this->assertEquals($entity->id, $model->id);
    }

    public function test_findBy_with_empty_table_should_return_null(): void
    {
        $model = $this->repository->findBy(where: ['id' => Str::ulid()]);
        $this->assertNull($model);
    }

    public function test_findBy_with_exists_data_should_return_model(): void
    {
        $entity = User::factory()->create();
        $model = $this->repository->findBy(where: ['id' => $entity->id]);

        $this->assertNotNull($model);
        $this->assertEquals($entity->id, $model->id);
    }

    public function test_findBy_with_softDeleted_data_should_return_model(): void
    {
        $entity = User::factory()->create();
        $entity->delete();
        $this->assertSoftDeleted('users', ['id' => $entity->id]);

        $model = $this->repository->findBy(where: ['id' => $entity->id], withTrashed: true);
        $this->assertNotNull($model);
        $this->assertEquals($entity->id, $model->id);
    }

    public function test_create_with_empty_array_should_return_null(): void
    {
        $model = $this->repository->create([]);
        $this->assertNull($model);
    }

    public function test_create_with_valid_entity_should_return_model(): void
    {
        $entity = User::factory()->make()->makeVisible("password")->toArray();
        $model = $this->repository->create($entity);

        $this->assertNotNull($model);
        $this->assertEquals($entity['name'], $model->name);
        $this->assertEquals($entity['email'], $model->email);
    }

    public function test_save_with_empty_array_should_return_null(): void
    {
        $model = $this->repository->save([]);
        $this->assertNull($model);
    }

    public function test_save_non_existing_data_should_return_new_model(): void
    {
        $entity = User::factory()->make()->makeVisible("password")->toArray();
        $model = $this->repository->save($entity);

        $this->assertNotNull($model);
        $this->assertEquals($entity['name'], $model->name);
        $this->assertEquals($entity['email'], $model->email);
    }

    public function test_save_existing_data_should_update_and_return_updated_model(): void
    {
        $entity = User::factory()->create();
        $updatedEntity = User::factory()->make()->makeVisible("password")->toArray();
        $model = $this->repository->save($updatedEntity, ['id' => $entity->id]);

        $this->assertNotNull($model);
        $this->assertEquals($updatedEntity['name'], $model->name);
        $this->assertEquals($updatedEntity['email'], $model->email);
    }

    public function test_save_existing_data_without_matcher_should_update_data_on_primaryKey_and_return_updated_model(): void
    {
        User::factory()->create();
        $entity = User::factory()->make()->makeVisible("password")->toArray();
        $model = $this->repository->save($entity);

        $this->assertNotNull($model);
        $this->assertEquals($entity['name'], $model->name);
        $this->assertEquals($entity['email'], $model->email);
    }

    public function test_save_softDeleted_data_should_return_updated_model(): void
    {
        $entity = User::factory()->create();
        $entity->delete();
        $this->assertSoftDeleted('users', ['id' => $entity->id]);

        $entity = $entity->toArray();
        $entity['name'] = 'new_name';
        $model = $this->repository->save($entity, ['id' => $entity['id']], true);

        $this->assertNotNull($model);
        $this->assertEquals($entity['name'], $model->name);
        $this->assertDatabaseHas('users', ['id' => $entity['id']]);
    }

    public function test_delete_non_existing_data_should_return_0(): void
    {
        $deleted = $this->repository->delete(Str::ulid());
        $this->assertEquals(0, $deleted);
    }

    public function test_delete_existing_data_should_return_1(): void
    {
        $entity = User::factory()->create();
        $deleted = $this->repository->delete($entity->id);

        $this->assertEquals(1, $deleted);
        $this->assertSoftDeleted('users', ['id' => $entity->id]);
    }

    public function test_forceDelete_non_existing_data_should_return_false(): void
    {
        $deleted = $this->repository->forceDelete(Str::ulid());
        $this->assertFalse($deleted);
    }

    public function test_forceDelete_existing_data_should_return_true(): void
    {
        $entity = User::factory()->create();
        $deleted = $this->repository->forceDelete($entity->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $entity->id]);
    }

    public function test_forceDelete_softDeleted_data_should_return_true(): void
    {
        $entity = User::factory()->create();
        $entity->delete();
        $this->assertSoftDeleted('users', ['id' => $entity->id]);

        $deleted = $this->repository->forceDelete($entity->id, true);
        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $entity->id]);
    }

    public function test_isExists_non_existing_data_should_return_false(): void
    {
        $exists = $this->repository->exists(Str::ulid());
        $this->assertFalse($exists);
    }

    public function test_isExists_existing_data_should_return_true(): void
    {
        $entity = User::factory()->create();
        $isExists = $this->repository->exists($entity->id);
        $this->assertTrue($isExists);
    }

    public function test_count_on_empty_table_should_return_0(): void
    {
        $count = $this->repository->count();
        $this->assertEquals(0, $count);
    }

    public function test_count_on_existing_data_should_return_number_of_records(): void
    {
        User::factory()->count(5)->create();
        $count = $this->repository->count();
        $this->assertEquals(5, $count);
    }
}
