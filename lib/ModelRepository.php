<?php

namespace CoreModel;

class ModelRepository
{
    /**
     * @var AbstractModel
     */
    protected $className;

    public function __construct($model = null)
    {
        $this->setModel($model);
    }

    /**
     * Sets the model.
     * @param $model
     */
    public function setModel($model)
    {
        $this->className = $model;
    }

    /**
     * Retrieves the class name of the model this repository is meant to represent.
     * @return AbstractModel
     */
    protected function getModel()
    {
        return $this->className;
    }

    /**
     * Finds a model by its ID.
     * @param $id
     * @param array $with
     * @return AbstractModel
     */
    public function find($id, $with = [])
    {
        $class = $this->getModel();
        return $class::with($with)->find($id);
    }

    /**
     * Finds a model, or fails and throws an exception.
     * @param $id
     * @param array $with
     * @return AbstractModel
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
     * Finds a model, or creates a new one.
     * @param $id
     * @param array $columns
     * @return \Illuminate\Support\Collection|static
     */
    public function findOrNew($id, array $columns = ['*'])
    {
        $class = $this->getModel();
        return $class::findOrNew($id, $columns);
    }

    /**
     * Creates a new model.
     * @param $data
     * @return AbstractModel
     */
    public function create($data)
    {
        $class = $this->getModel();
        return $class::create($data);
    }

    /**
     * Finds the first instance, or creates a new model (immediately saving it)
     * @param array $attributes
     * @param array $with
     * @return static
     */
    public function firstOrCreate(array $attributes, $with = [])
    {
        $class = $this->getModel();
        $model = $class::firstOrCreate($attributes);
        $model->load($with);
        return $model;
    }

    /**
     * Finds the first instance, or creates a new model (without saving it)
     * @param array $attributes
     * @return static
     */
    public function firstOrNew(array $attributes)
    {
        $class = $this->getModel();
        return $class::firstOrNew($attributes);
    }

    /**
     * Retrieves all records from a database.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*'])
    {
        $class = $this->getModel();
        return $class::all($columns);
    }

    /**
     * Retrieves the first record based on a where.
     *
     * @param $column
     * @param $operator - (=, >, <, <>, etc)
     * @param $value
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function whereFirst($column, $operator, $value, $with = [])
    {
        $className = $this->getModel();
        return $className::with($with)->where($column, $operator, $value)->first();
    }

    /**
     * Retrieves all records based on a where.
     *
     * @param $column
     * @param $operator
     * @param $value
     * @return mixed
     */
    public function whereGet($column, $operator, $value)
    {
        $className = $this->getModel();
        return $className::where($column, $operator, $value)->get();
    }

    /**
     * Instantiates a new model, and returns it.
     *
     * @param array $attrs
     * @return AbstractModel
     */
    public function newModel(array $attrs = [])
    {
        $className = $this->getModel();
        return new $className($attrs);
    }

    /**
     * @param array $with
     * @return \Illuminate\Database\Query\Builder
     */
    public function with($with = [])
    {
        $className = $this->getModel();
        return $className::with($with);
    }

    /**
     * Creates a query builder instance, and returns it.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $className = $this->getModel();
        return $className::query();
    }
}