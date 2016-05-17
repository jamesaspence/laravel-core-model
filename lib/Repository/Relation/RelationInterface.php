<?php

namespace Laracore\Repository\Relation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

interface RelationInterface
{

    /**
     * Sets a relation on a model.
     *
     * @param Model $model
     * @param $relation
     * @param $value
     * @return Model
     */
    public function setRelation(Model $model, $relation, $value);

    /**
     * Sets relations on a model.
     *
     * @param Model $model
     * @param $relations
     * @return Model
     */
    public function setRelations(Model $model, $relations);

    /**
     * Sets touched relations on a model.
     *
     * @param Model $model
     * @param array $touches
     * @return mixed
     */
    public function setTouchedRelations(Model $model, $touches = []);

    /**
     * Associates a model with a relation.
     *
     * @param Model $model
     * @param $relation
     * @param Model $value
     * @return Model
     */
    public function associateRelation(Model $model, $relation, Model $value);

    /**
     * Associates many relations with a model.
     *
     * @param Model $model
     * @param array $relations
     * @return Model
     */
    public function associateMany(Model $model, $relations);

    /**
     * Dissociates a model with a relation.
     *
     * @param Model $model
     * @param $relation
     * @return Model
     */
    public function dissociateRelation(Model $model, $relation);

    /**
     * Attaches a relation based on id.
     *
     * @param Model $model
     * @param $relation
     * @param $modelId
     * @param array $tableAttributes
     * @return Model
     */
    public function attachRelation(Model $model, $relation, $modelId, $tableAttributes = []);

    /**
     * Detaches a relation based on id.
     *
     * @param Model $model
     * @param $relation
     * @param null $modelId
     * @return Model
     */
    public function detachRelation(Model $model, $relation, $modelId = null);

    /**
     * Updates an existing pivot on a relation.
     *
     * @param Model $model
     * @param $relation
     * @param $id
     * @param array $tableAttributes
     * @return Model
     */
    public function updateExistingPivot(Model $model, $relation, $id, $tableAttributes = []);

    /**
     * Syncs the relations on a model with ids.
     *
     * @param Model $model
     * @param $relation
     * @param array $ids
     * @return Model
     */
    public function sync(Model $model, $relation, $ids = []);

    /**
     * Saves many relations.
     *
     * @param Model $model
     * @param $relation
     * @param $value
     * @return Relation
     */
    public function saveMany(Model $model, $relation, $value);

    /**
     * Saves a relation.
     *
     * @param Model $model
     * @param $relation
     * @param Model $value
     * @param array $tableAttributes
     * @return Relation
     */
    public function save(Model $model, $relation, Model $value, $tableAttributes = []);

}