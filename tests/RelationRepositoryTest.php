<?php
/**
 * Created by PhpStorm.
 * User: trentrogers
 * Date: 5/24/16
 * Time: 12:54 PM
 */

namespace Tests;

use Laracore\Repository\Relation\RelationRepository;
use Illuminate\Database\Eloquent\Model;

class RelationRepositoryTest extends TestCase
{
    /**
     * @var RelationRepository
     */
    protected $relationRepository;

    public function setUp()
    {
        parent::setUp();

//        $repository = \Mockery::mock(RelationRepository::class);

        $repository = new RelationRepository();

        $this->relationRepository = $repository;
    }

    public function testSetRelation()
    {
        $model = \Mockery::mock(Model::class);
        $model->shouldReceive('setRelation')
            ->once()
            ->andReturnSelf();
        
        $result = $this->relationRepository->setRelation($model, 'stuff', 'things');

        $this->assertInstanceOf(Model::class, $result);
    }
    
    public function testSetRelations()
    {
        $model = \Mockery::mock(Model::class);
        $model->shouldReceive('setRelations')
            ->once()
            ->andReturnSelf();

        $result = $this->relationRepository->setRelations($model, 'stuff');

        $this->assertInstanceOf(Model::class, $result);
    }

    public function testSetTouchedRelations()
    {
        $model = \Mockery::mock(Model::class);
        $model->shouldReceive('setTouchedRelations')
            ->once()
            ->andReturnSelf();

        $result = $this->relationRepository->setTouchedRelations($model, 'stuff');
        $this->assertInstanceOf(Model::class, $result);
    }

    public function testAssociateRelation()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testAssociateMany()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testDissociateRelation()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testAttachRelation()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testDetachRelation()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testUpdateExistingPivot()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testSync()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testSaveMany()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testSave()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testRelationsIsA()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testRelationIsBelongsTo()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testRelationsIsBelongsToMany()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testRelationIsHasOne()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function testRelationIsHasMany()
    {
        $this->stub();
        //TODO fill in test contents.
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }
}