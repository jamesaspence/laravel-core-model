<?php

namespace Laracore\Factory;

use Illuminate\Database\Eloquent\Model;
use Laracore\Exception\NoRepositoryToInstantiateException;
use Laracore\Repository\RepositoryInterface;

interface FactoryInterface
{
    /**
     * Retrieves a repository.
     * Fails if the repository cannot be instantiated.
     *
     * @return RepositoryInterface
     * @throws NoRepositoryToInstantiateException
     */
    public function getRepository();

    /**
     * Sets the repository on the factory.
     *
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository);

    /**
     * Sets the model on the repository.
     *
     * @param $className
     */
    public function setModel($className);

    /**
     * Makes a new model with attributes
     *
     * @param array $attributes
     * @param array $associatedRelations
     * @return Model
     */
    public function make(array $attributes = [], array $associatedRelations = []);

    /**
     * Adds the associated relations on a model.
     * Will save and return if $save is set to true.
     * This method should only be used for BelongsTo relations!!
     *
     * @param Model $model
     * @param array $associatedRelations
     * @param bool $save
     * @return Model
     */
    public function addAssociatedRelations(Model $model, array $associatedRelations, $save = false);

    /**
     * Instantiates the repository.
     *
     * @return RepositoryInterface
     * @throws NoRepositoryToInstantiateException
     */
    public function instantiateRepository();

}