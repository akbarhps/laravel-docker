<?php

namespace App\Traits\Model;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait to record the fingerprint of the user
 * who created, updated, or deleted a record.
 *
 * This trait is used in the model.
 * This trait requires the user to be logged in.
 *
 * @package App\Traits
 */
trait HasFingerprint
{
    /**
     * Table column name of the CREATED_BY
     *
     * @var string
     */
    protected string $CREATED_BY;

    /**
     * Table column name of the UPDATED_BY
     *
     * @var string
     */
    protected string $UPDATED_BY;

    /**
     * Table column name of the DELETED_BY
     *
     * @var string
     */
    protected string $DELETED_BY;

    /**
     * Boot the trait.
     *
     * @return void
     * @throws Exception
     */
    public static function bootHasFingerprint(): void
    {
        static::creating(function (Model $model) {
            if (!$this->CREATED_BY) {
                throw new Exception('CREATED_BY is not defined in Model class');
            }
            if (!$this->UPDATED_BY) {
                throw new Exception('UPDATED_BY is not defined in Model class');
            }

            $userId = auth()->id();
            $model->{$this->CREATED_BY} = $userId;
            $model->{$this->UPDATED_BY} = $userId;
        });

        static::updating(function (Model $model) {
            if (!$this->UPDATED_BY) {
                throw new Exception('UPDATED_BY is not defined in Model class');
            }

            $model->{$this->UPDATED_BY} = auth()->id();
        });

        static::deleting(function (Model $model) {
            if (!method_exists($model, 'trashed')) {
                return;
            }
            if (!$this->DELETED_BY) {
                throw new Exception('DELETED_BY is not defined in Model class');
            }

            // when deleting record, we can't add the deleted_by directly
            // because deleting record is triggered after the record is deleted (soft delete)
            // so, we need to save it again after the deletion process
            $model->{$this->DELETED_BY} = auth()->id();
            $model->save();
        });
    }
}
