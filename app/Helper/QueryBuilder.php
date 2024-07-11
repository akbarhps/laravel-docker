<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QueryBuilder
{
    private mixed $query;

    private function __construct($query)
    {
        $this->query = $query;
    }

    public static function builder(Model|string $table): QueryBuilder
    {
        if (is_string($table)) {
            return new self(DB::table($table));
        }

        return new self($table::query());
    }

    public function select(array $select): QueryBuilder
    {
        if ($select) {
            $this->query->select($select);
        }

        return $this;
    }

    public function where(array $where): QueryBuilder
    {
        if ($where) {
            $this->query->where($where);
        }

        return $this;
    }

    public function orWhere(array $where): QueryBuilder
    {
        if ($where) {
            $this->query->orWhere($where);
        }

        return $this;
    }

    public function whereBetween(string $column, array $where): QueryBuilder
    {
        if ($where) {
            $this->query->whereBetween($column, $where);
        }

        return $this;
    }

    public function whereIn(string $column, array $where): QueryBuilder
    {
        if ($where) {
            $this->query->whereIn($column, $where);
        }

        return $this;
    }

    public function join(string $destTable, string $originCol, string $operator, string $destCol): QueryBuilder
    {
        $this->query->join($destTable, $originCol, $operator, $destCol);
        return $this;
    }

    public function leftJoin(string $destTable, string $originCol, string $operator, string $destCol): QueryBuilder
    {
        $this->query->leftJoin($destTable, $originCol, $operator, $destCol);
        return $this;
    }

    public function rightJoin(string $destTable, string $originCol, string $operator, string $destCol): QueryBuilder
    {
        $this->query->rightJoin($destTable, $originCol, $operator, $destCol);
        return $this;
    }

    public function crossJoin(string $destTable, string $originCol, string $operator, string $destCol): QueryBuilder
    {
        $this->query->crossJoin($destTable, $originCol, $operator, $destCol);
        return $this;
    }

    public function orderBy(array $orderBy): QueryBuilder
    {
        foreach ($orderBy as $column => $direction) {
            $this->query->orderBy($column, $direction);
        }

        return $this;
    }

    public function withTrashed(bool $withTrashed): QueryBuilder
    {
        if ($withTrashed) {
            $this->query->withTrashed();
        }

        return $this;
    }

    public function paging(int $page, int $limitPerPage): QueryBuilder
    {
        if ($page > 0) {
            $this->query->offset(($page - 1) * $limitPerPage);
        }

        if ($limitPerPage > 0) {
            $this->query->limit($limitPerPage);
        }

        return $this;
    }

    public function build()
    {
        return $this->query;
    }
}
