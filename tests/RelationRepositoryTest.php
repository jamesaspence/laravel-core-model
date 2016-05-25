<?php
/**
 * Created by PhpStorm.
 * User: trentrogers
 * Date: 5/24/16
 * Time: 12:54 PM
 */

namespace Tests;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Laracore\Repository\Relation\RelationRepository;
use Illuminate\Database\Eloquent\Model;
use Mockery\Mock;

class RelationRepositoryTest extends TestCase
{
    /**
     * @var RelationRepository|Mock
     */
    protected $relationRepository;

    public function setUp()
    {
        parent::setUp();

        $repository = \Mockery::mock(RelationRepository::class)->makePartial();

        $this->relationRepository = $repository;
    }

    public function createMockModel()
    {
        return \Mockery::mock(Model::class);
    }

    public function createMockRelation()
    {
        return \Mockery::mock(BelongsTo::class);
    }

    public function testSetRelation()
    {
        $model = $this->createMockModel();
        $model->shouldReceive('setRelation')
            ->once()
            ->andReturnSelf();
        
        $result = $this->relationRepository->setRelation($model, 'stuff', 'things');

        $this->assertInstanceOf(Model::class, $result);
    }
    
    public function testSetRelations()
    {
        $model = $this->createMockModel();
        $relations = [
            'relation' => $this->createMockModel()
        ];
        $model->shouldReceive('setRelations')
            ->with($relations)
            ->once();

        $this->relationRepository->setRelations($model, $relations);
    }

    public function testSetTouchedRelations()
    {
        $model = $this->createMockModel();
        $relations = [
            'relation'
        ];
        $model->shouldReceive('setTouchedRelations')
            ->with($relations)
            ->once()
            ->andReturnSelf();

        $result = $this->relationRepository->setTouchedRelations($model, $relations);
        $this->assertInstanceOf(Model::class, $result);
    }

    public function testAssociateRelation()
    {
        $model = $this->createMockModel();
        $relation = 'relation';
        $value = $this->createMockModel();

        $mockRelation = \Mockery::mock(BelongsTo::class);
        $mockRelation
            ->shouldReceive('associate')
            ->with($value)
            ->once()
            ->andReturn($model);

        $model
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation);

        $this->relationRepository->associateRelation($model, $relation, $value);
    }

    public function testAssociateMany()
    {
        $model = $this->createMockModel();

        $relations = [
            'relation' => $this->createMockModel(),
            'otherRelation' => $this->createMockModel()
        ];

        foreach ($relations as $relation => $value) {
            $this
                ->relationRepository
                ->shouldReceive('associateRelation')
                ->with($model, $relation, $value)
                ->andReturn($model);
        };

        $this->relationRepository->associateMany($model, $relations);
    }

    public function testDissociateRelation()
    {
        $relation = 'relation';
        $mockRelation = $this->createMockRelation();
        $mockRelation
            ->shouldReceive('dissociate')
            ->once();

        $model = $this->createMockModel();
        $model
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation);

        $this->relationRepository->dissociateRelation($model, $relation);
    }

    public function testAttachRelation()
    {
        $relation = 'relation';
        $modelId = 1;
        $tableAttributes = ['stuff' => 'things'];
        $mockRelation = $this->createMockRelation();
        $mockRelation
            ->shouldReceive('attach')
            ->with($modelId, $tableAttributes)
            ->once();

        $model = $this->createMockModel();
        $model
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation);

        $this->relationRepository->attachRelation($model, $relation, $modelId, $tableAttributes);
    }

    public function testDetachRelation()
    {
        $relation = 'relation';
        $modelId = 1;
        $mockRelation = $this->createMockRelation();
        $mockRelation
            ->shouldReceive('detach')
            ->with($modelId)
            ->once();

        $model = $this->createMockModel();
        $model
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation);

        $this->relationRepository->detachRelation($model, $relation, $modelId);
    }

    public function testUpdateExistingPivot()
    {
        $relation = 'relation';
        $id = 1;
        $tableAttributes = ['stuff' => 'things'];
        $mockRelation = $this->createMockRelation();
        $mockRelation
            ->shouldReceive('updateExistingPivot')
            ->with($id, $tableAttributes)
            ->once();

        $model = $this->createMockModel();
        $model
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation);

        $this->relationRepository->updateExistingPivot($model, $relation, $id, $tableAttributes);
    }

    public function testSync()
    {
        $relation = 'relation';
        $ids = [1, 2];
        $mockRelation = $this
            ->createMockRelation()
            ->shouldReceive('sync')
            ->with($ids)
            ->once()
            ->getMock();

        $model = $this
            ->createMockModel()
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation)
            ->getMock();

        $this->relationRepository->sync($model, $relation, $ids);
    }

    public function testSaveMany()
    {
        $relation = 'relation';
        $value = $this->createMockModel();
        $mockRelation = $this
            ->createMockRelation()
            ->shouldReceive('saveMany')
            ->with($value)
            ->once()
            ->getMock();

        $model = $this
            ->createMockModel()
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation)
            ->getMock();

        $this->relationRepository->saveMany($model, $relation, $value);
    }

    public function testSave()
    {
        $relation = 'relation';
        $value = $this->createMockModel();
        $tableAttributes = ['stuff' => 'things'];
        $mockRelation = $this
            ->createMockRelation()
            ->shouldReceive('save')
            ->with($value, $tableAttributes)
            ->once()
            ->getMock();

        $model = $this
            ->createMockModel()
            ->shouldReceive($relation)
            ->once()
            ->andReturn($mockRelation)
            ->getMock();

        $this->relationRepository->save($model, $relation, $value, $tableAttributes);
    }

    public function testRelationsIsA()
    {
        $relation = \Mockery::mock(Relation::class);
        $expected = Relation::class;

        $this->assertTrue($this->relationRepository->relationsIsA($relation, $expected));

        //Test for failure too
        $this->assertFalse($this->relationRepository->relationsIsA($relation, BelongsTo::class));
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }
}