<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasPrefixedId
{
    protected static function bootHasPrefixedId()
    {
        static::creating(function ($model) {
            $model->incrementing = false;
            $model->keyType = 'string';

            $prefix = $model->prefix ?? Str::lower(class_basename($model));

            if (empty($model->{$model->getKeyName()})) {
              do{
                $randomString = Str::random(10);
                $id = $prefix . '-' . $randomString;
              } while ($model->where($model->getKeyName(), $id)->exists());
              
              $model->{$model->getKeyName()} = $id;
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}