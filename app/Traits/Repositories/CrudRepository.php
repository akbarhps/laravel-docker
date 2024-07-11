<?php

namespace App\Traits\Repositories;

use App\Helper\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait CrudRepository
{
    protected readonly Model $model;
    protected $primaryKey;

    /**
     * Find all records of the model
     *
     * Return the collection of the model
     *
     * @param array $select
     * @param array $where
     * @param array $orderBy
     * @param bool $withTrashed
     * @param int $page
     * @param int $limit
     * @return Collection|array
     */
    public function findAll(array $select = ["*"],
                            array $where = [],
                            array $orderBy = [],
                            bool  $withTrashed = false,
                            int   $page = 0,
                            int   $limit = 0): Collection|array
    {
        return QueryBuilder::builder($this->model)
            ->select($select)
            ->where($where)
            ->orderBy($orderBy)
            ->withTrashed($withTrashed)
            ->paging($page, $limit)
            ->build()
            ->get();
    }

    /**
     * Get model record by primary key
     *
     * @param string $primaryKey
     * @param array $select
     * @param bool $withTrashed
     * @return Model|null
     */
    public function findById(string $primaryKey,
                             array  $select = ["*"],
                             bool   $withTrashed = false): Model|null
    {
        return QueryBuilder::builder($this->model)
            ->select($select)
            ->withTrashed($withTrashed)
            ->build()
            ->find($primaryKey);
    }

    /**
     * Get model record by where condition
     *
     * @param array $where
     * @param array $select
     * @param bool $withTrashed
     * @return Model|null
     */
    public function findBy(array $where,
                           array $select = ["*"],
                           bool  $withTrashed = false): Model|null
    {
        return QueryBuilder::builder($this->model)
            ->where($where)
            ->select($select)
            ->withTrashed($withTrashed)
            ->build()
            ->first();
    }

    /**
     * Create new record of the model
     *
     * @param array $entity
     * @return Model|null
     */
    public function create(array $entity): Model|null
    {
        if (!$entity) {
            return null;
        }

        return $this->model::create($entity);
    }

    /**
     * Update or create a new record of the model
     * If the primary key or matchers array is provided,
     * it will update the record else it will create a new record
     *
     * Return the model instance
     *
     * @param array $entity
     * @param array $matchers
     * @param bool $withTrashed
     * @return Model|null
     */
    public function save(array $entity, array $matchers = [], bool $withTrashed = false): Model|null
    {
        if (!$entity) {
            return null;
        }
        if (!$matchers) {
            $entityPrimaryKey = $entity[$this->primaryKey] ?? null;
            if (!$entityPrimaryKey) {
                return self::create($entity);
            }

            $matchers = [$this->primaryKey => $entityPrimaryKey];
        }

        return QueryBuilder::builder($this->model)
            ->withTrashed($withTrashed)
            ->build()
            ->updateOrCreate($matchers, $entity);
    }

    /**
     * Update a record of the model using the primary key
     *
     * Return the model instance
     *
     * @param string $primaryKey
     * @param array $entity
     * @param bool $withTrashed
     * @return Model|null
     */
    public function update(string $primaryKey, array $entity, bool $withTrashed = false): Model|null
    {
        return QueryBuilder::builder($this->model)
            ->withTrashed($withTrashed)
            ->where([$this->primaryKey => $primaryKey])
            ->build()
            ->update($entity);
    }

    /**
     * Delete a record of the model using the primary key,
     * if model has deleted_at, it will be soft deleted else it will be hard deleted.
     *
     * Return the number of rows affected
     *
     * @param string $primaryKey
     * @return int
     */
    public function delete(string $primaryKey): int
    {
        return $this->model::destroy($primaryKey);
    }

    /**
     * Force delete a record of the model using the primary key
     *
     * Return boolean whether the record was deleted or not
     *
     * @param string $primaryKey
     * @param bool $withTrashed
     * @return bool
     */
    public function forceDelete(string $primaryKey, bool $withTrashed = false): bool
    {
        return QueryBuilder::builder($this->model)
            ->withTrashed($withTrashed)
            ->where([$this->primaryKey => $primaryKey])
            ->build()
            ->forceDelete();
    }

    /**
     * Check if a record exists in the model using the primary key
     *
     * Return boolean whether the record exists or not
     *
     * @param string $primaryKey
     * @param bool $withTrashed
     * @return bool
     */
    public function exists(string $primaryKey, bool $withTrashed = false): bool
    {
        return QueryBuilder::builder($this->model)
            ->withTrashed($withTrashed)
            ->where([$this->primaryKey => $primaryKey])
            ->build()
            ->exists();
    }

    /**
     * Count the number of records in the model
     *
     * return the number of records
     *
     * @param bool $withTrashed
     * @return int
     */
    public function count(bool $withTrashed = false): int
    {
        return QueryBuilder::builder($this->model)
            ->withTrashed($withTrashed)
            ->build()
            ->count();
    }
}
