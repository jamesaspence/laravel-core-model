<?php

namespace Tests\Stub;

use Illuminate\Database\Eloquent\Model;

class ModelStub extends Model
{
    public $guarded = ['id'];

    public function save(array $options = [])
    {
        return true;
    }

}