<?php


namespace Laracore\Criteria;



use Laracore\Exception\NoCriteriaClosureSetException;

class ModelCriteria implements CriteriaInterface
{
    protected $skipped = false;

    protected $persistent = false;

    private $closure;

    public function __construct(\Closure $closure = null, $skips = false, $persists = false)
    {
        if (!is_null($closure)) {
            $this->setClosure($closure);
        }

        $this->skip($skips);
        $this->persist($persists);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($model)
    {
        if ($this->shouldBeSkipped()) {
            return $model;
        }

        $closure = $this->getClosure();
        return $closure($model);
    }

    /**
     * {@inheritdoc}
     */
    public function setClosure(\Closure $closure)
    {
        $this->closure = $closure;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getClosure()
    {
        if (!$this->closure instanceof \Closure) {
            throw new NoCriteriaClosureSetException;
        }

        return $this->closure;
    }

    /**
     * {@inheritdoc}
     */
    public function skip($status = true)
    {
        $this->skipped = $status;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldBeSkipped()
    {
        return $this->skipped;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($status = true)
    {
        $this->persistent = $status;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPersistent()
    {
        return $this->persistent;
    }
}