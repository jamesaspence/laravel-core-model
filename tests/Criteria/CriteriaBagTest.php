<?php


namespace Laracore\Tests\Criteria;


use Laracore\Criteria\CriteriaBag;
use Laracore\Criteria\ModelCriteria;
use Laracore\Tests\Stub\ModelStub;
use Laracore\Tests\TestCase;

class CriteriaBagTest extends TestCase
{

    /**
     * @return CriteriaBag
     */
    public function getCriteriaBag()
    {
        return new CriteriaBag;
    }

    public function testAdd()
    {
        $mock = \Mockery::mock(ModelCriteria::class);

        $criteria = $this->getCriteriaBag()->add($mock);

        $this->assertEquals($mock, $criteria->all()[0]);
    }

    public function testAll()
    {
        $this->assertTrue(is_array($this->getCriteriaBag()->all()));
    }

    public function testApplyAll()
    {
        $mock = \Mockery::mock(ModelCriteria::class);

        $model = \Mockery::mock(ModelStub::class);

        $mock->shouldReceive('apply')->once()->andReturn($model);

        $criteria = $this->getCriteriaBag()->add($mock);
        $return = $criteria->applyAll($model);

        $this->assertEquals($model, $return);
    }

    public function testClear()
    {
        $mock = \Mockery::mock(ModelCriteria::class);

        $criteria = $this->getCriteriaBag()->add($mock);

        $criteria->clear();

        $this->assertTrue(count($criteria->all()) === 0);
    }

    public function testClearNonPersistent()
    {
        $mock = \Mockery::mock(ModelCriteria::class)->makePartial();

        $criteria = $this->getCriteriaBag()->add($mock);

        $persistentMock = \Mockery::mock(ModelCriteria::class)->makePartial();
        $persistentMock->persist(true);

        $criteria->add($persistentMock);

        $criteria->clearNonPersistent();

        $this->assertTrue(count($criteria->all()) === 1);
        $this->assertEquals($persistentMock, $criteria->all()[0]);
    }

    public function testCriteriaToApply()
    {
        $mock = \Mockery::mock(ModelCriteria::class)->makePartial();

        $criteria = $this->getCriteriaBag()->add($mock);

        $skippedMock = \Mockery::mock(ModelCriteria::class)->makePartial();
        $skippedMock->skip();

        $criteria->add($skippedMock);

        $this->assertTrue($criteria->criteriaToApply());
    }

}