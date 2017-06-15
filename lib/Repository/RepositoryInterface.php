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
     * Sets an attribute on the model.
     *
     * @param Model $model
     * @param $key
     * @param $value
     * @return Model
     */
    public function setAttribute(Model $model, $key, $value);

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
     * @return Builder
     */
    public function with($with = []);

    /**
     * Creates a query builder instance, and returns it.
     *
     * @return Builder
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
     * Updates or creates a model based on conditions.
     * @see Builder::updateOrCreate()
     *
     * @param array $attributes
     * @param array $values
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $values = []);

    /**
     * Deletes a model.
     *
     * @param Model $model
     */
    public function delete(Model $model);

    /**
     * Deletes the models based on id.
     * @see Model::destroy()
     *
     * @param array|int $ids
     * @return mixed
     */
    public function destroy($ids);

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

    /**
     * Builds a query with soft-deleted models.
     *
     * @return Builder
     */
    public function withTrashed();

    /**
     * Starts a query without global scopes.
     * @see Model::newQueryWithoutScope()
     * @see Model::newQueryWithoutScopes()
     *
     * @param mixed $scopes
     * @return Builder
     */
    public function withoutGlobalScopes($scopes);
}