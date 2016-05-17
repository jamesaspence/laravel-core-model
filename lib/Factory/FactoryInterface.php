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
     * @return ModelRepository
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
     * Makes a new model with the attributes and relations.
     *
     * @param array $attributes
     * @param array $relations
     * @return Model
     */
    public function make(array $attributes = [], array $relations = []);

    /**
     * Sets the relations on a model.
     *
     * @param Model $model
     * @param array $relations
     * @return Model
     */
    public function setRelationsForModel(Model $model, array $relations = []);

    /**
     * Instantiates the repository.
     *
     * @return RepositoryInterface
     * @throws NoRepositoryToInstantiateException
     */
    public function instantiateRepository();

}