<?php


namespace Laracore\Tests\Criteria;


use Laracore\Criteria\CriteriaBag;
use Laracore\Criteria\CriteriaInterface;
use Laracore\Repository\ModelRepository;
use Laracore\Tests\Stub\ModelStub;
use Laracore\Tests\TestCase;

class CriteriaModelRepositoryTest extends TestCase
{

    public function getRepository()
    {
        return \Mockery::mock(ModelRepository::class)->makePartial();
    }

    public function getMockCriteriaBag()
    {
        return \Mockery::mock(CriteriaBag::class);
    }

    public function testPostQuery()
    {
        $repository = $this->getRepository();
        $repository->shouldReceive('clearCriteria')
            ->once();

        $repository->postQuery();
    }

    /**
     * @covers CriteriaModelRepository::getCriteria
     */
    public function testSetCriteriaBag()
    {
        $repository = $this->getRepository();

        $criteriaBag = $this->getMockCriteriaBag();

        $repository->setCriteriaBag($criteriaBag);

        $this->assertEquals($criteriaBag, $repository->getCriteria());
    }

    /**
     * @covers CriteriaModelRepository::getCriteria
     * @covers CriteriaModelRepository::setCriteriaBag
     */
    public function testAddCriteria()
    {
        $criteriaBag = $this->getMockCriteriaBag();

        $criteriaBag->shouldReceive('add')->once();

        $repository = $this->getRepository();
        $repository->setCriteriaBag($criteriaBag);

        $repository->addCriteria(\Mockery::mock(CriteriaInterface::class));
    }

    public function getCriteriaWithNoBagSet()
    {
        $repository = $this->getRepository();

        $criteria = $repository->getCriteria();

        $this->assertInstanceOf(CriteriaBag::class, $criteria);
    }

    public function getCriteriaWithPreExistentBag()
    {
        $repository = $this->getRepository();

        $criteria = $this->getMockCriteriaBag();

        $repository->setCriteriaBag($criteria);

        $this->assertEquals($criteria, $repository->getCriteria());
    }

    public function testClearCriteriaWithClearPersistent()
    {
        $criteriaBag = $this->getMockCriteriaBag();
        $criteriaBag->shouldReceive('clear')->once();

        $repository = $this->getRepository();

        $repository->setCriteriaBag($criteriaBag);

        $repository->clearCriteria(true);
    }

    public function testClearCriteriaWithoutClearPersistent()
    {
        $criteriaBag = $this->getMockCriteriaBag();
        $criteriaBag->shouldReceive('clearNonPersistent')->once();

        $repository = $this->getRepository();

        $repository->setCriteriaBag($criteriaBag);

        $repository->clearCriteria();
    }

    public function testApplyCriteria()
    {
        $model = new ModelStub();

        $criteriaBag = $this->getMockCriteriaBag();
        $criteriaBag
            ->shouldReceive('applyAll')
            ->with($model)
            ->once()
            ->andReturn($model);

        $repository = $this->getRepository();

        $repository->setCriteriaBag($criteriaBag);

        $result = $repository->applyCriteria($model);

        $this->assertEquals($result, $model);
    }

    public function testSetDefaultCriteria()
    {
        $repository = $this->getRepository();

        $repository
            ->shouldReceive('setCriteriaBag')
            ->once();

        $repository->setDefaultCriteria();
    }

}