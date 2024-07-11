<?php

namespace App\Traits\Repositories;

use App\Helper\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

trait PagingRepository
{
    protected readonly Model $model;
    protected readonly int $limitPerPage;

    public function findAllWithCursor(array $select = ["*"],
                                      array $where = [],
                                      array $orderBy = [],
                                      int   $limitPerPage = 0,
                                      bool  $withTrashed = false)
    {
        return QueryBuilder::builder($this->model)
            ->select($select)
            ->withTrashed($withTrashed)
            ->where($where)
            ->orderBy($orderBy)
            ->build()
            ->cursorPaginate($limitPerPage ?: $this->limitPerPage);
    }

    public function findAllWithPaging(array $select = ["*"],
                                      array $where = [],
                                      array $orderBy = [],
                                      int   $limitPerPage = 0,
                                      bool  $withTrashed = false)
    {
        return QueryBuilder::builder($this->model)
            ->select($select)
            ->withTrashed($withTrashed)
            ->where($where)
            ->orderBy($orderBy)
            ->build()
            ->paginate($limitPerPage ?: $this->limitPerPage);
    }
}
