<?php
/**
 * Created by PhpStorm.
 * User: trentrogers
 * Date: 5/23/16
 * Time: 1:55 PM
 */

namespace Tests;


use Laracore\Factory\FactoryInterface;
use Laracore\Repository\ModelRepository;
use Laracore\Repository\RepositoryInterface;
use Laracore\Factory\ModelFactory;
use Laracore\Exception\NoRepositoryToInstantiateException;
use Laracore\Exception\RelationNotBelongsToException;
use Mockery\Mock;
use Illuminate\Database\Eloquent\Model;

class ModelFactoryTest extends TestCase
{
    /**
     * @var ModelFactory|Mock
     */
    private $factory;
    
    public function setUp()
    {
        parent::setUp();

        $factory = \Mockery::mock(ModelFactory::class)->makePartial();

        $this->factory = $factory;
    }
    
    public function testSetAndGetRepository()
    {
        $repository = \Mockery::mock(RepositoryInterface::class);

        $this->factory->setRepository($repository);

        $this->assertEquals($this->factory->getRepository(), $repository);
    }

    /**
     * @expectedException \Laracore\Exception\NoRepositoryToInstantiateException
     */
    public function testInstantiateRepository()
    {
        $this->factory->instantiateRepository();
    }

    public function testMake()
    {
        $attributes = [
            'stuff' => 'things'
        ];

        $associatedRelations = [
            'relation' => \Mockery::mock(Model::class)
        ];

        $model = \Mockery::mock(Model::class);

        $repository = \Mockery::mock(ModelRepository::class);
        $repository
            ->shouldReceive('newModel')
            ->with($attributes)
            ->andReturn($model);

        $repository
            ->shouldReceive('save')
            ->with($model)
            ->once();

        $this
            ->factory
            ->shouldReceive('getRepository')
            ->andReturn($repository);

        $this
            ->factory
            ->shouldReceive('addAssociatedRelations')
            ->with($model, $associatedRelations)
            ->once();

        $this->factory->make($attributes, $associatedRelations);
    }

    /**
     * @expectedException \Laracore\Exception\RelationNotBelongsToException
     */
    public function testAddAssociatedRelations()
    {
        $model = \Mockery::mock(Model::class);

        $repository = \Mockery::mock(RepositoryInterface::class);
        $repository->shouldReceive('getRelationRepository')
            ->andReturnSelf();

        $repository->shouldReceive('relationIsBelongsTo')
            ->andReturn(false);

        $this->factory->setRepository($repository);

        $this->factory->addAssociatedRelations($model, ['stuff' => 'things'], false);
    }
}