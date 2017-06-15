<?php

namespace Laracore\Repository;

use Illuminate\Database\Eloquent\Model;
use Laracore\Exception\RelationInterfaceExceptionNotSetException;
use Laracore\Repository\Relation\RelationInterface;
use Laracore\Repository\Relation\RelationRepository;

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

    public function __construct($model = null, RelationInterface $repository = null)
    {
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
        return $this
            ->newModel()
            ->with($with)
            ->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrFail($id, $with = [])
    {
        return $this
            ->newModel()
            ->with($with)
            ->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrNew($id, array $columns = ['*'])
    {
        return $this
            ->newModel()
            ->findOrNew($id, $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function create($data)
    {
        return $this
            ->newModel()
            ->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrCreate(array $attributes, $with = [])
    {
        return $this
            ->newModel()
            ->firstOrCreate($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrNew(array $attributes)
    {
        return $this
            ->newModel()
            ->firstOrNew($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function all($columns = ['*'])
    {
        return $this
            ->newModel()
            ->all($columns);
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
        return $this
            ->newModel()
            ->with($with);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        return $this
            ->newModel()
            ->query();
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
        return $this
            ->newModel()
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
    public function paginate($perPage = 10, $with = [])
    {
        return $this
            ->newModel()
            ->with($with)
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function whereFirst($column, $operator, $value, $with = [])
    {
        return $this
            ->newModel()
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
            ->newModel()
            ->with($with)
            ->where($column, $operator, $value)
            ->get();
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
    public function withoutGlobalScopes($scopes = null)
    {
        return $this->newModel()->withoutGlobalScopes($scopes);
    }

    /**
     * {@inheritdoc}
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->newModel()->updateOrCreate($attributes, $values);
    }
}