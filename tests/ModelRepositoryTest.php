<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Laracore\Repository\ModelRepository;
use Laracore\Repository\Relation\RelationInterface;
use Mockery\Mock;

class ModelRepositoryTest extends TestCase
{
    /**
     * @var ModelRepository|Mock
     */
    private $repository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $repository = \Mockery::mock(ModelRepository::class)->makePartial();

        $this->repository = $repository;
    }

    public function createMockModel()
    {
        $model = \Mockery::mock(Model::class);
        return $model;
    }

    public function testSetModelAndGetModel()
    {
        $className = 'Test';
        $this->repository->setModel($className);
        $this->assertEquals($this->repository->getModel(), $className);
    }

    public function testSetAndGetRelationRepository()
    {
        $repository = \Mockery::mock(RelationInterface::class);
        $this->repository->setRelationRepository($repository);
        $this->assertEquals($repository, $this->repository->getRelationRepository());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetRelationRepositoryIncorrectClass()
    {
        $this->repository->setRelationRepository(null);
    }

    public function testNewModel()
    {
        $this->repository->setModel($this->createMockModel());
        $result = $this->repository->newModel();
        $this->assertInstanceOf(Model::class, $result);
    }
}