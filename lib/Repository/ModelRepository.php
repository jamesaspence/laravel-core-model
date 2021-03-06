<?php

namespace Laracore\Repository;

use Illuminate\Database\Eloquent\Model;
use Laracore\Exception\ModelClassNotSetException;
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
        $model = (is_null($model) ? $this->getDefaultModel() : $model);

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
        if (!is_null($model)) {
            $this->className = $model;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultModel()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        if (is_null($this->className)) {
            throw new ModelClassNotSetException('A model class must be set on this ModelRepository instance.');
        }

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
    public function create(array $attributes = [])
    {
        return $this
            ->query()
            ->create($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function forceCreate(array $attributes = [])
    {
        return $this
            ->query()
            ->forceCreate($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrCreate(array $attributes, $with = [])
    {
        $model = $this
            ->newModel()
            ->firstOrCreate($attributes);

        $this->load($model, $with);

        return $model;
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

    /**
     * {@inheritdoc}
     */
    public function destroy($ids)
    {
        $className = $this->getModel();
        return $className::destroy($ids);
    }

    /**
     * {@inheritdoc}
     */
    public function withTrashed()
    {
        return $this->newModel()->withTrashed();
    }

    /**
     * Our default method caller.
     * Delegates our method calls off to the model class itself,
     * ensuring that custom functions (like query scopes) are
     * supported.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        /*
         * If our first argument is an instance of a model, we
         * invoke our method on the model instance, passing in the
         * remaining arguments.
         * Likewise, if we have a singular argument and it's an
         * instance of a mode, we invoke our method on that instance.
         */
        if (is_array($arguments) && $arguments[0] instanceof Model) {
            /** @var Model $model */
            $model = $arguments[0];
            unset($arguments[0]);

            return $model->$name(...$arguments);
        } elseif ($arguments instanceof Model) {
            /** @var Model $model */
            $model = $arguments;

            return $model->$name();
        }

        return $this->newModel()->$name(...$arguments);
    }
}