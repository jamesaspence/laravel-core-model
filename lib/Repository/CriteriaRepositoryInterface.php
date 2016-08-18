<?php


namespace Laracore\Repository;


use Illuminate\Database\Eloquent\Builder;
use Laracore\Criteria\CriteriaBag;
use Laracore\Criteria\CriteriaInterface;

interface CriteriaRepositoryInterface
{
    /**
     * Sets the criteria bag.
     *
     * @param CriteriaBag $bag
     * @return static
     */
    public function setCriteriaBag(CriteriaBag $bag);

    /**
     * Adds a criteria.
     *
     * @param CriteriaInterface $criteria
     * @return static
     */
    public function addCriteria(CriteriaInterface $criteria);

    /**
     * Retrieves the criteria bag.
     *
     * @return CriteriaBag
     */
    public function getCriteria();

    /**
     * Clears the criteria bag.
     *
     * @param bool $clearPersistent
     * @return static
     */
    public function clearCriteria($clearPersistent = false);

    /**
     * Applies the criteria.
     *
     * @param $model
     * @return Builder
     */
    public function applyCriteria($model);

    /**
     * Retrieves the default criteria on the class.
     *
     * @return CriteriaBag
     */
    public function getDefaultCriteria();

}