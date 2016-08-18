<?php

namespace Laracore\Repository;

use Illuminate\Database\Eloquent\Model;
use Laracore\Criteria\CriteriaBag;
use Laracore\Criteria\CriteriaInterface;
use Laracore\Exception\RelationInterfaceExceptionNotSetException;
use Laracore\Repository\Relation\RelationInterface;
use Laracore\Repository\Relation\RelationRepository;

class ModelRepository implements RepositoryInterface, CriteriaRepositoryInterface
{
    /**
     * @var CriteriaBag
     */
    private $criteria;

    /**
     * @var Model
     */
    protected $className;

    /**
     * @var RelationInterface
     */
    protected $relationRepository;

    public function __construct($model = null, RelationInterface $repository = null)
    {
        $this->setCriteriaBag($this->getDefaultCriteria());
        $this->setModel($model);
        if (is_null($repository)) {
            $repository = new RelationRepository();
        }
        $this->setRelationRepository($repository);
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($model)
    {
        $this->className = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute(Model $model, $key, $value)
    {
        $model->$key = $value;

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id, $with = [])
    {
        $result = $this
            ->newModel()
            ->with($with)
            ->find($id);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function findOrFail($id, $with = [])
    {
        $result = $this
            ->newModel()
            ->with($with)
            ->findOrFail($id);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function findOrNew($id, array $columns = ['*'])
    {
        $result = $this
            ->newModel()
            ->findOrNew($id, $columns);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function create($data)
    {
        $result = $this
            ->newModel()
            ->create($data);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrCreate(array $attributes, $with = [])
    {
        $model = $this
            ->newModel()
            ->firstOrCreate($attributes);

        $this->postQuery();

        $result = $this->load($model, $with);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrNew(array $attributes)
    {
        $result = $this
            ->newModel()
            ->firstOrNew($attributes);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function all($columns = ['*'])
    {
        $result = $this
            ->newModel()
            ->all($columns);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function newModel(array $attrs = [])
    {
        $className = $this->getModel();
        return $this->applyCriteria(new $className($attrs));
    }

    /**
     * {@inheritdoc}
     */
    public function with($with = [])
    {
        $result = $this
            ->newModel()
            ->with($with);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $result = $this
            ->newModel()
            ->query();

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Model $model, array $options = [])
    {
        $model->save($options);
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function fill(Model $model, array $attributes = [])
    {
        $model->fill($attributes);
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function fillAndSave(Model $model, array $attributes = [])
    {
        $model = $this->fill($model, $attributes);
        return $this->save($model);
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationRepository()
    {
        if (!isset($this->relationRepository)) {
            throw new RelationInterfaceExceptionNotSetException;
        }

        return $this->relationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function setRelationRepository(RelationInterface $repository)
    {
        $this->relationRepository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function select($columns = '*')
    {
        $result = $this
            ->newModel()
            ->select($columns);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function update(Model $model, array $updatedValues)
    {
        return $this->fillAndSave($model, $updatedValues);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Model $model)
    {
        $model->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage = 10, $with = [])
    {
        $result = $this
            ->newModel()
            ->with($with)
            ->paginate($perPage);

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function whereFirst($column, $operator, $value, $with = [])
    {
        $result = $this
            ->newModel()
            ->with($with)
            ->where($column, $operator, $value)
            ->first();

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function whereGet($column, $operator, $value, $with = [])
    {
        $result = $this
            ->newModel()
            ->with($with)
            ->where($column, $operator, $value)
            ->get();

        $this->postQuery();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function load(Model $model, $relations = [])
    {
        $model->load($relations);
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function postQuery()
    {
        $this->clearCriteria();
        return $this;
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

    /**
     * {@inheritdoc}
     */
    public function getDefaultCriteria()
    {
        return new CriteriaBag();
    }
}