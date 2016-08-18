<?php


namespace Laracore\Tests\Criteria;


use Illuminate\Database\Eloquent\Builder;
use Laracore\Criteria\ModelCriteria;
use Laracore\Tests\Stub\ModelStub;
use Laracore\Tests\TestCase;

class ModelCriteriaTest extends TestCase
{

    public function getModelCriteria()
    {
        return \Mockery::mock(ModelCriteria::class)->makePartial();
    }

    public function testApply()
    {
        $criteria = $this->getModelCriteria();

        $criteria->shouldReceive('shouldBeSkipped')
            ->once()
            ->andReturn(false);

        $builder = \Mockery::mock(Builder::class);

        $closure = function ($model) use ($builder) {
            return $builder;
        };

        $criteria->shouldReceive('getClosure')
            ->once()
            ->andReturn($closure);

        $result = $criteria->apply(new ModelStub());

        $this->assertEquals($result, $builder);
    }

    public function testApplyShouldBeSkipped()
    {
        $criteria = $this->getModelCriteria();

        $criteria->shouldReceive('shouldBeSkipped')
            ->once()
            ->andReturn(true);

        $criteria->shouldNotReceive('getClosure');

        $model = new ModelStub();

        $result = $criteria->apply($model);

        $this->assertEquals($result, $model);
    }

    /**
     * @covers ModelCriteria::getClosure
     * @covers ModelCriteria::setClosure
     */
    public function testSetAndGetClosure()
    {
        $criteria = $this->getModelCriteria();

        $function = function ($model) {
            return $model;
        };

        $criteria->setClosure($function);

        $this->assertEquals($function, $criteria->getClosure());
    }

    /**
     * @covers ModelCriteria::skip
     * @covers ModelCriteria::shouldBeSkipped
     */
    public function testSkipAndShouldBeSkipped()
    {
        $criteria = $this->getModelCriteria();

        $criteria->skip();

        $this->assertTrue($criteria->shouldBeSkipped());

        $criteria->skip(false);

        $this->assertFalse($criteria->shouldBeSkipped());
    }

    /**
     * @covers ModelCriteria::persist
     * @covers ModelCriteria::isPersistent
     */
    public function testPersistAndIsPersistent()
    {
        $criteria = $this->getModelCriteria();

        $criteria->persist();

        $this->assertTrue($criteria->isPersistent());

        $criteria->persist(false);

        $this->assertFalse($criteria->isPersistent());
    }
}