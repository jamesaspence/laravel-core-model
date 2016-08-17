<?php

namespace Laracore\Criteria;



use Illuminate\Database\Eloquent\Builder;
use Laracore\Exception\NoCriteriaClosureSetException;

interface CriteriaInterface
{
    /**
     * Applies the criteria to the query.
     *
     * @param $model
     * @return Builder
     */
    public function apply($model);

    /**
     * Sets the closure to be applied for the criteria.
     *
     * @param \Closure $closure
     * @return static
     */
    public function setClosure(\Closure $closure);

    /**
     * Retrieves the criteria's closure.
     *
     * @throws NoCriteriaClosureSetException
     * @return \Closure
     */
    public function getClosure();

    /**
     * Marks a criteria as needing to be skipped.
     *
     * @param bool $status
     * @return static
     */
    public function skip($status = true);

    /**
     * Checks if a criteria should be skipped.
     *
     * @return bool
     */
    public function shouldBeSkipped();

    /**
     * Marks a criteria as persistent. (i.e. not being cleared after the query)
     *
     * @param bool $status
     * @return static
     */
    public function persistent($status = true);

    /**
     * Checks if the criteria is meant to persist.
     *
     * @return bool
     */
    public function isPersistent();
}