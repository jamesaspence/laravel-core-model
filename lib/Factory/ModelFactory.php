<?php

namespace Laracore\Factory;

use Illuminate\Database\Eloquent\Model;
use Laracore\Exception\NoRepositoryToInstantiateException;
use Laracore\Repository\RepositoryInterface;

class ModelFactory implements FactoryInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * Retrieves a repository.
     * Fails if the repository cannot be instantiated.
     *
     * @return RepositoryInterface
     * @throws NoRepositoryToInstantiateException
     */
    public function getRepository()
    {
        if (!isset($this->repository)) {
            $this->repository = $this->instantiateRepository();
        }

        return $this->repository;
    }

    /**
     * Sets the repository on the factory.
     *
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Sets the model on the repository.
     *
     * @param $className
     */
    public function setModel($className)
    {
        $this->repository->setModel($className);
    }

    /**
     * Makes a new model with the attributes and relations.
     *
     * @param array $attributes
     * @param array $relations
     * @return Model
     */
    public function make(array $attributes = [], array $relations = [])
    {
        $model = $this->getRepository()->newModel($attributes);
        $model = $this->setRelationsForModel($model, $relations);
        return $this->repository->save($model);
    }

    /**
     * Sets the relations on a model.
     *
     * @param Model $model
     * @param array $relations
     * @return Model
     */
    public function setRelationsForModel(Model $model, array $relations = [])
    {
        foreach ($relations as $key => $value) {
            $model->$key()->associate($value);
        }
        return $model;
    }

    /**
     * Instantiates the repository if it needs to be. Can be overridden.
     *
     * @return RepositoryInterface
     * @throws NoRepositoryToInstantiateException
     */
    public function instantiateRepository()
    {
        throw new NoRepositoryToInstantiateException('Can\'t instantiate repository for ModelFactory. Make sure to set repository via setRepository method.');
    }

}