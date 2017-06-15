<?php


namespace Laracore\Tests\Stub;


use Laracore\Repository\ModelRepository;

class ModelRepositoryWithDefaultModel extends ModelRepository
{

    public function getDefaultModel()
    {
        return ModelStub::class;
    }

}