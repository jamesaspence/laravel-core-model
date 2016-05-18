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
        $model =  $this
            ->newModel()
            ->firstOrCreate($attributes);

        return $this->load($model, $with);
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
     * Retrieves the relation repository.
     *
     * @return RelationRepository
     * @throws RelationInterfaceExceptionNotSetException
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
            ->newModel()
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

    /**
     * {@inheritdoc}
     */
    public function load(Model $model, $relations = [])
    {
        $model->load($relations);
        return $model;
    }
}