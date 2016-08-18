<?php


namespace Laracore\Criteria;


class CriteriaBag
{
    /**
     * @var CriteriaInterface[]
     */
    protected $criteria = [];

    /**
     * Adds a criteria to the bag.
     *
     * @param CriteriaInterface $criteria
     * @return static
     */
    public function add(CriteriaInterface $criteria)
    {
        $this->criteria[] = $criteria;
        return $this;
    }

    /**
     * Retrieves all criteria.
     *
     * @return CriteriaInterface[]
     */
    public function all()
    {
        return $this->criteria;
    }

    /**
     * Applies all criteria.
     *
     * @param $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyAll($model)
    {
        /** @var CriteriaInterface $criteria */
        foreach ($this->criteria as $criteria) {
            $model = $criteria->apply($model);
        }

        return $model;
    }

    /**
     * Clears the criteria.
     *
     * @return static
     */
    public function clear()
    {
       $this->criteria = [];

        return $this;
    }

    /**
     * Clears non-persistent criteria.
     *
     * @return static
     */
    public function clearNonPersistent()
    {
        $newCriteria = [];
        foreach ($this->criteria as $criteria) {
            if ($criteria->isPersistent()) {
                $newCriteria[] = $criteria;
            }
        }

        $this->criteria = $newCriteria;

        return $this;
    }

    /**
     * Checks if there are criteria to apply.
     *
     * @return bool
     */
    public function criteriaToApply()
    {
        $count = 0;

        foreach ($this->criteria as $criteria) {
            if (!$criteria->shouldBeSkipped()) {
                ++$count;
            }
        }

        return $count > 0;
    }

}