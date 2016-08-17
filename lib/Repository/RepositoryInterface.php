<?php

namespace Laracore\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Laracore\Repository\Relation\RelationInterface;

interface RepositoryInterface
{

    /**
     * Sets the model.
     * @param $model
     */
    public function setModel($model);

    /**
     * Retrieves the class name of the model this repository is meant to represent.
     * @return Model
     */
    public function getModel();

    /**
     * Finds a model by its ID.
     * @param $id
     * @param array $with
     * @return Model
     */
    public function find($id, $with = []);

    /**
     * Finds a model, or fails and throws an exception.
     * @param $id
     * @param array $with
     * @return Model
     */
    public function findOrFail($id, $with = []);

    /**
     * Finds a model, or creates a new one.
     * @param $id
     * @param array $columns
     * @return \Illuminate\Support\Collection|static
     */
    public function findOrNew($id, array $columns = ['*']);

    /**
     * Creates a new model.
     * @param $data
     * @return Model
     */
    public function create($data);

    /**
     * Finds the first instance, or creates a new model (immediately saving it)
     * @param array $attributes
     * @param array $with
     * @return Model
     */
    public function firstOrCreate(array $attributes, $with = []);

    /**
     * Finds the first instance, or creates a new model (without saving it)
     * @param array $attributes
     * @return Model
     */
    public function firstOrNew(array $attributes);

    /**
     * Retrieves all records from a database.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*']);

    /**
     * Instantiates a new model, and returns it.
     *
     * @param array $attrs
     * @return Model
     */
    public function newModel(array $attrs = []);

    /**
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($with = []);

    /**
     * Creates a query builder instance, and returns it.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query();

    /**
     * Saves a model.
     *
     * @param Model $model
     * @param array $options
     * @return Model
     */
    public function save(Model $model, array $options = []);

    /**
     * Fills a model with attributes.
     *
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function fill(Model $model, array $attributes = []);

    /**
     * Fills a model, then saves it.
     *
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function fillAndSave(Model $model, array $attributes = []);

    /**
     * Retrieves the relation repository.
     *
     * @return RelationInterface
     */
    public function getRelationRepository();

    /**
     * Sets the relation repository.
     *
     * @param RelationInterface $repository
     * @return mixed
     */
    public function setRelationRepository(RelationInterface $repository);

    /**
     * Creates a query builder for select.
     *
     * @param string $columns
     * @return Builder
     */
    public function select($columns = '*');

    /**
     * Updates a model.
     *
     * @param Model $model
     * @param array $updatedValues
     * @return Model
     */
    public function update(Model $model, array $updatedValues);

    /**
     * Deletes a model.
     *
     * @param Model $model
     */
    public function delete(Model $model);

    /**
     * Retrieves paginated results.
     *
     * @param int $perPage
     * @param mixed $with
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 10, $with = []);

    /**
     * Retrieves the first result based on a single-column search.
     *
     * @param $column
     * @param $operator
     * @param $value
     * @param mixed $with
     * @return Model |null
     */
    public function whereFirst($column, $operator, $value, $with = []);

    /**
     * Retrieves a collection of results based on a single-column search.
     *
     * @param $column
     * @param $operator
     * @param $value
     * @param mixed $with
     * @return Collection
     */
    public function whereGet($column, $operator, $value, $with = []);

    /**
     * Loads relations on a model.
     *
     * @param Model $model
     * @param array $relations
     * @return Model
     */
    public function load(Model $model, $relations = []);
}