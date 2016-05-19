<?php

namespace Laracore\Repository\Relation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;

class RelationRepository implements RelationInterface
{

    /**
     * {@inheritdoc}
     */
    public function setRelation(Model $model, $relation, $value)
    {
        $model->setRelation($relation, $value);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function setRelations(Model $model, $relations)
    {
        $model->setRelations($relations);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function setTouchedRelations(Model $model, $touches = [])
    {
        $model->setTouchedRelations($touches);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function associateRelation(Model $model, $relation, Model $value)
    {
        $model->$relation()->associate($value);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function associateMany(Model $model, $relations)
    {
        foreach ($relations as $relation => $value) {
            $model = $this->associateRelation($model, $relation, $value);
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function dissociateRelation(Model $model, $relation)
    {
        $model->$relation()->dissociate();

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function attachRelation(Model $model, $relation, $modelId, $tableAttributes = [])
    {
        $model->$relation()->attach($modelId, $tableAttributes);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function detachRelation(Model $model, $relation, $modelId = null)
    {
        $model->$relation()->detach($modelId);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function updateExistingPivot(Model $model, $relation, $id, $tableAttributes = [])
    {
        $model->$relation()->updateExistingPivot($id, $tableAttributes);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function sync(Model $model, $relation, $ids = [])
    {
        $model->$relation()->sync($ids);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function saveMany(Model $model, $relation, $value)
    {
        $model->$relation()->saveMany($value);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Model $model, $relation, Model $value, $tableAttributes = [])
    {
        $model->$relation()->save($value, $tableAttributes);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function relationsIsA(Relation $relation, $expected)
    {
        return is_a($relation, $expected);
    }

    /**
     * Checks if a relation is a BelongsTo relation.
     *
     * @param Model $model
     * @param Relation $relation
     * @return bool
     */
    public function relationIsBelongsTo(Model $model, Relation $relation)
    {
        return $this->relationsIsA($model->$relation(), BelongsTo::class);
    }

    /**
     * Checks if a relation is a BelongsTo relation.
     *
     * @param Model $model
     * @param Relation $relation
     * @return bool
     */
    public function relationIsBelongsToMany(Model $model, Relation $relation)
    {
        return $this->relationsIsA($model->$relation(), BelongsToMany::class);
    }

    /**
     * Checks if a relation is a BelongsTo relation.
     *
     * @param Model $model
     * @param Relation $relation
     * @return bool
     */
    public function relationIsHasOne(Model $model, Relation $relation)
    {
        return $this->relationsIsA($model->$relation(), HasOne::class);
    }

    /**
     * Checks if a relation is a BelongsTo relation.
     *
     * @param Model $model
     * @param Relation $relation
     * @return bool
     */
    public function relationIsHasMany(Model $model, Relation $relation)
    {
        return $this->relationsIsA($model->$relation(), HasMany::class);
    }
}