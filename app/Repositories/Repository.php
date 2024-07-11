<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    protected readonly Model $model;
    protected readonly string $table;
    protected $primaryKey;
    protected readonly string $keyType;
    protected readonly int $limitPerPage;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->table = $model->getTable();
        $this->primaryKey = $model->getKeyName();
        $this->keyType = $model->getKeyType();
        $this->limitPerPage = $model->getPerPage();
    }
}
