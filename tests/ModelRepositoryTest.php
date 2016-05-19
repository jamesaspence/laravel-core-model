<?php

namespace Tests;

use Laracore\Repository\ModelRepository;
use Laracore\Repository\Relation\RelationInterface;

class ModelRepositoryTest extends TestCase
{
    /**
     * @var ModelRepository
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

    public function testFind()
    {
        $this->stub();
    }

    public function testFindOrFail()
    {
        $this->stub();
    }

    public function testFindOrNew()
    {
        $this->stub();
    }

    public function create()
    {
        $this->stub();
    }

    public function testFirstOrCreate()
    {
        $this->stub();
    }

    public function testFirstOrNew()
    {
        $this->stub();
    }

    public function testAll()
    {
        $this->stub();
    }

    public function testNewModel()
    {
        $this->stub();
    }

    public function testWith()
    {
        $this->stub();
    }

    public function testQuery()
    {
        $this->stub();
    }

    public function testSave()
    {
        $this->stub();
    }

    public function testFill()
    {
        $this->stub();
    }

    public function testFillAndSave()
    {
        $this->stub();
    }

    public function testSelect()
    {
        $this->stub();
    }

    public function testUpdate()
    {
        $this->stub();
    }

    public function testDelete()
    {
        $this->stub();
    }

    public function testPaginate()
    {
        $this->stub();
    }

    public function testWhereFirst()
    {
        $this->stub();
    }

    public function testWhereGet()
    {
        $this->stub();
    }

    public function testLoad()
    {
        $this->stub();
    }
}