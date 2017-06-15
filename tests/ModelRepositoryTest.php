<?php

namespace Laracore\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laracore\Exception\ModelClassNotSetException;
use Laracore\Repository\ModelRepository;
use Laracore\Repository\Relation\RelationInterface;
use Laracore\Tests\Stub\ModelRepositoryWithDefaultModel;
use Laracore\Tests\Stub\ModelStubWithScopes;
use Mockery\Mock;
use Mockery\MockInterface;
use Laracore\Tests\Stub\ModelStub;

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

    /**
     * Sets up the newModel mock on the repository.
     *
     * @param MockInterface $model
     * @return ModelRepository|Mock
     */
    public function setUpNewModelMock(MockInterface $model)
    {
        $this->repository->shouldReceive('newModel')->andReturn($model)->byDefault();
        return $this->repository;
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

    public function testConstructorSetsModelAndRelationInterface()
    {
        $relationMock = \Mockery::mock(RelationInterface::class);

        $repository = new ModelRepository(ModelStub::class, $relationMock);

        $this->assertEquals(ModelStub::class, $repository->getModel());
        $this->assertEquals($relationMock, $repository->getRelationRepository());
    }

    public function testGetModelReturnsDefaultModel()
    {
        $repository = new ModelRepositoryWithDefaultModel();

        $this->assertEquals($repository->getModel(), ModelStub::class);
    }

    /**
     * @expectedException \Laracore\Exception\ModelClassNotSetException
     */
    public function testGetModelThrowsExceptionWithNoDefaultSet()
    {
        $this->repository->getModel();
    }

    public function testSetAndGetRelationRepository()
    {
        /** @var RelationInterface $repository */
        $repository = \Mockery::mock(RelationInterface::class);
        $this->repository->setRelationRepository($repository);
        $this->assertEquals($repository, $this->repository->getRelationRepository());
    }

    /**
     * @expectedException \Laracore\Exception\RelationInterfaceExceptionNotSetException
     */
    public function testGetRelationRepositoryNoRepositorySet()
    {
        $this->repository->getRelationRepository();
    }

    public function testNewModel()
    {
        $this->repository->setModel(ModelStub::class);
        $model = $this->repository->newModel([
            'stuff' => 'things'
        ]);

        $this->assertInstanceOf(ModelStub::class, $model);
        $data = $model->getAttributes();
        $this->assertArrayHasKey('stuff', $data);
        $this->assertTrue($data['stuff'] == 'things');
    }

    public function testLoad()
    {
        $relations = ['stuff'];
        $model = $this->createMockModel();
        $model->shouldReceive('load')->once()->with($relations);

        $result = $this->repository->load($model, $relations);

        $this->assertInstanceOf(Model::class, $result);
    }

    public function testFind()
    {
        $id = 1;
        $model = $this->createMockModel();

        $model->shouldReceive('with')->once()->andReturnSelf();
        $model->shouldReceive('find')->with($id)->once()->andReturnSelf();

        $this->setUpNewModelMock($model);

        $result = $this->repository->find($id);

        $this->assertInstanceOf(Model::class, $result);
    }

    public function testFindOrFail()
    {
        $id = 1;
        $model = $this->createMockModel();

        $model->shouldReceive('with')->once()->andReturnSelf();
        $model->shouldReceive('findOrFail')->with($id)->once()->andReturnSelf();

        $this->setUpNewModelMock($model);

        $result = $this->repository->findOrFail($id);

        $this->assertInstanceOf(Model::class, $result);
    }

    public function testFindOrNew()
    {
        $id = 1;
        $columns = ['unique', 'columns'];
        $model = $this->createMockModel();

        $model->shouldReceive('findOrNew')
            ->with($id, $columns)
            ->once()
            ->andReturnSelf();

        $this->setUpNewModelMock($model);

        $result = $this->repository->findOrNew($id, $columns);

        $this->assertInstanceOf(Model::class, $result);
    }

    public function testCreate()
    {
        $data = ['stuff' => 'things'];

        $builder = \Mockery::mock(Builder::class);


        $model = $this->createMockModel();
        $model
            ->shouldReceive('query')
            ->once()
            ->andReturn($builder);

        $builder
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($model);

        $this->setUpNewModelMock($model);

        $this->repository->create($data);
    }

    public function testForceCreate()
    {
        $data = ['stuff' => 'things'];

        $builder = \Mockery::mock(Builder::class);

        $model = $this->createMockModel();
        $model
            ->shouldReceive('query')
            ->once()
            ->andReturn($builder);

        $builder
            ->shouldReceive('forceCreate')
            ->with($data)
            ->once()
            ->andReturn($model);

        $this->setUpNewModelMock($model);

        $this->repository->forceCreate($data);
    }

    public function testFirstOrCreate()
    {
        $attributes = ['stuff' => 'things'];
        $with = ['relation.subRelation'];

        $model = $this->createMockModel();

        $model
            ->shouldReceive('firstOrCreate')
            ->with($attributes)
            ->once()
            ->andReturnSelf();

        $this->repository
            ->shouldReceive('load')
            ->with($model, $with)
            ->once()
            ->andReturn($model);

        $this->setUpNewModelMock($model);

        $this->repository->firstOrCreate($attributes, $with);
    }

    public function testFirstOrNew()
    {
        $attributes = ['stuff' => 'things'];

        $model = $this->createMockModel();

        $model
            ->shouldReceive('firstOrNew')
            ->with($attributes)
            ->andReturnSelf();

        $this->setUpNewModelMock($model);

        $this->repository->firstOrNew($attributes);
    }

    public function testAll()
    {
        $columns = ['columns'];

        $model = $this->createMockModel();
        $model
            ->shouldReceive('all')
            ->with($columns)
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->all($columns);
    }

    public function testWith()
    {
        $with = ['relation.subRelation'];

        $model = $this->createMockModel();
        $model
            ->shouldReceive('with')
            ->with($with)
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->with($with);
    }

    public function testQuery()
    {
        $model = $this->createMockModel();

        $model
            ->shouldReceive('query')
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->query();
    }

    public function testSave()
    {
        $model = $this->createMockModel();
        $options = [
            'stuff' => 'things'
        ];

        $model
            ->shouldReceive('save')
            ->with($options)
            ->once();

        $this->repository->save($model, $options);
    }

    public function testFill()
    {
        $model = $this->createMockModel();

        $model
            ->shouldReceive('fill')
            ->once();

        $this->repository->fill($model);
    }

    public function testFillAndSave()
    {
        $model = $this->createMockModel();

        $model
            ->shouldReceive('fill')
            ->once();

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->andReturn($model);

        $this->repository->fillAndSave($model);
    }

    public function testSelect()
    {
        $columns = ['column'];

        $model = $this->createMockModel();

        $model
            ->shouldReceive('select')
            ->with($columns)
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->select($columns);
    }

    public function testUpdate()
    {
        $model = $this->createMockModel();
        $updatedValues = ['updated' => 'value'];

        $this
            ->repository
            ->shouldReceive('fillAndSave')
            ->with($model, $updatedValues)
            ->once();

        $this->repository->update($model, $updatedValues);
    }

    public function testDelete()
    {
        $model = $this->createMockModel();

        $model
            ->shouldReceive('delete')
            ->once();
        
        $this->repository->delete($model);
    }

    public function testPaginate()
    {
        $with = ['relation.subRelation'];
        $perPage = 20;

        $model = $this->createMockModel();

        $model
            ->shouldReceive('with')
            ->with($with)
            ->once()
            ->andReturnSelf();
        $model
            ->shouldReceive('paginate')
            ->with($perPage)
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->paginate($perPage, $with);
    }

    public function testWhereFirst()
    {
        $column = 'column';
        $operator = '';
        $value = 'value';
        $with = ['relation.subRelation'];

        $model = $this->createMockModel();

        $model
            ->shouldReceive('with')
            ->with($with)
            ->once()
            ->andReturnSelf();

        $model
            ->shouldReceive('where')
            ->with($column, $operator, $value)
            ->once()
            ->andReturnSelf();

        $model
            ->shouldReceive('first')
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->whereFirst($column, $operator, $value, $with);
    }

    public function testWhereGet()
    {
        $column = 'column';
        $operator = '';
        $value = 'value';
        $with = ['relation.subRelation'];

        $model = $this->createMockModel();

        $model
            ->shouldReceive('with')
            ->with($with)
            ->once()
            ->andReturnSelf();

        $model
            ->shouldReceive('where')
            ->with($column, $operator, $value)
            ->once()
            ->andReturnSelf();

        $model
            ->shouldReceive('get')
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->whereGet($column, $operator, $value, $with);
    }

    public function testWithoutGlobalScopes()
    {
        $methodName = 'withoutGlobalScopes';
        $model = \Mockery::mock(ModelStubWithScopes::class);

        $firstArgument = 'test';
        $secondArgument = 'test2';
        $thirdArgument = [$firstArgument, $secondArgument];

        $model->shouldReceive($methodName)
            ->with(null)
            ->once();

        $model->shouldReceive($methodName)
            ->with($firstArgument)
            ->once();

        $model->shouldReceive($methodName)
            ->with($secondArgument)
            ->once();

        $model->shouldReceive($methodName)
            ->with($thirdArgument)
            ->once();

        $this->setUpNewModelMock($model);

        $this->repository->withoutGlobalScopes();
        $this->repository->withoutGlobalScopes($firstArgument);
        $this->repository->withoutGlobalScopes($secondArgument);
        $this->repository->withoutGlobalScopes($thirdArgument);
    }
}