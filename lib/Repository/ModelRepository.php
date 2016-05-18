<?php

namespace Laracore\Repository;

use Illuminate\Database\Eloquent\Model;
use Laracore\Exception\RelationInterfaceExceptionNotSetException;
use Laracore\Repository\Relation\RelationInterface;

class ModelRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $className;

    /**
     * @var RelationInterface
     */
    protected $relationRepository;

    public function __construct($model = null)
    {
        $this->setModel($model);
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
    public function find($id, $with = [])
    {
        $class = $this->getModel();
        return $class::with($with)->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrFail($id, $with = [])
    {
        $class = $this->getModel();
        /** @var AbstractModel $model */
        $model = $class::findOrFail($id);
        $model->load($with);
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findOrNew($id, array $columns = ['*'])
    {
        $class = $this->getModel();
        return $class::findOrNew($id, $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function create($data)
    {
        $class = $this->getModel();
        return $class::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrCreate(array $attributes, $with = [])
    {
        $class = $this->getModel();
        $model = $class::firstOrCreate($attributes);
        $model->load($with);
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrNew(array $attributes)
    {
        $class = $this->getModel();
        return $class::firstOrNew($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function all($columns = ['*'])
    {
        $class = $this->getModel();
        return $class::all($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function newModel(array $attrs = [])
    {
        $className = $this->getModel();
        return new $className($attrs);
    }

    /**
     * {@inheritdoc}
     */
    public function with($with = [])
    {
        $className = $this->getModel();
        return $className::with($with);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $className = $this->getModel();
        return $className::query();
    }

    /**
     * {@inheritdoc}
     */
    public function save(Model $model)
    {
        $model->save();
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
        return $this
            ->query()
            ->select($columns);
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
    public function paginate($perPage = 10)
    {
        return $this
            ->getModel()
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function whereFirst($column, $operator, $value, $with = [])
    {
        return $this
            ->query()
            ->with($with)
            ->where($column, $operator, $value)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function whereGet($column, $operator, $value, $with = [])
    {
        return $this
            ->query()
            ->with($with)
            ->where($column, $operator, $value)
            ->get();
    }
}