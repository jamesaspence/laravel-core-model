<?php

namespace CoreModel;

use CoreModel\Exception\NoRepositoryToInstantiateException;

class ModelFactory
{
    /**
     * @var ModelRepository
     */
    protected $repository;

    /**
     * Retrieves a repository.
     * Fails if the repository cannot be instantiated.
     *
     * @return ModelRepository
     * @throws NoRepositoryToInstantiateException
     */
    protected function getRepository()
    {
        if (!isset($this->repository)) {
            $this->repository = $this->instantiateRepository();
        }

        return $this->repository;
    }

    /**
     * Sets the repository on the factory.
     *
     * @param ModelRepository $repository
     */
    public function setRepository(ModelRepository $repository)
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
     * @return AbstractModel
     */
    public function make(array $attributes = [], array $relations = [])
    {
        $model = $this->getRepository()->newModel($attributes);
        $model = $this->setRelationsForModel($model, $relations);
        $model->save();
        return $model;
    }

    /**
     * Sets the relations on a model.
     *
     * @param AbstractModel $model
     * @param array $relations
     * @return AbstractModel
     */
    protected function setRelationsForModel(AbstractModel $model, array $relations = [])
    {
        foreach ($relations as $key => $value) {
            $model->$key()->associate($value);
        }
        return $model;
    }

    /**
     * Instantiates the repository if it needs to be. Can be overridden.
     *
     * @return ModelRepository
     * @throws NoRepositoryToInstantiateException
     */
    protected function instantiateRepository()
    {
        throw new NoRepositoryToInstantiateException('Can\'t instantiate repository for ModelFactory. Make sure to set repository via setRepository method.');
    }

}