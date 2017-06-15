<?php


namespace Laracore\Tests\Stub;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ModelStubWithScopes extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('test', function (Builder $builder) {
            $builder->where('test', '>', 200);
        });

        static::addGlobalScope('test2', function (Builder $builder) {
            $builder->where('test2', '<', 100);
        });
    }

}