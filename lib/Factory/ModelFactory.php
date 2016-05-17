<?php

namespace Laracore\Factory;

use Laracore\Exception\NoRepositoryToInstantiateException;
use Laracore\Repository\RepositoryInterface;

class ModelFactory implements FactoryInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        if (!isset($this->repository)) {
            $this->repository = $this->instantiateRepository();
        }

        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($className)
    {
        $this->repository->setModel($className);
    }

    /**
     * {@inheritdoc}
     */
    public function make(array $attributes = [], array $relations = [])
    {
        $model = $this->getRepository()->newModel($attributes);

        $model = $this
            ->getRepository()
            ->getRelationRepository()
            ->associateMany($model, $relations);

        return $this->repository->save($model);
    }

    /**
     * {@inheritdoc}
     */
    public function instantiateRepository()
    {
        throw new NoRepositoryToInstantiateException('Can\'t instantiate repository for ModelFactory. Make sure to set repository via setRepository method.');
    }

}