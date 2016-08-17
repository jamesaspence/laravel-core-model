<?php


namespace Laracore\Repository;


use Laracore\Criteria\CriteriaBag;
use Laracore\Criteria\CriteriaInterface;

class CriteriaModelRepository extends ModelRepository implements CriteriaRepositoryInterface
{
    /**
     * @var CriteriaBag
     */
    private $criteria;

    /**
     * {@inheritdoc}
     */
    public function newModel(array $attrs = [])
    {
        return $this->applyCriteria(parent::newModel($attrs));
    }

    /**
     * {@inheritdoc}
     */
    public function postQuery()
    {
        $this->clearCriteria();
        return parent::postQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function setCriteriaBag(CriteriaBag $bag)
    {
        $this->criteria = $bag;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCriteria(CriteriaInterface $criteria)
    {
        $this->getCriteria()->add($criteria);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCriteria()
    {
        if (!$this->criteria instanceof CriteriaBag) {
            $this->criteria = new CriteriaBag();
        }

        return $this->criteria;
    }

    /**
     * {@inheritdoc}
     */
    public function clearCriteria($clearPersistent = false)
    {
        if ($clearPersistent) {
            $this->getCriteria()->clear();
        } else {
            $this->getCriteria()->clearNonPersistent();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function applyCriteria($model)
    {
        $model = $this->getCriteria()->applyAll($model);
        return $model;
    }
}