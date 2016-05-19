<?php

namespace Laracore\Factory;

use Laracore\Exception\RelationNotBelongsToException;
use Illuminate\Database\Eloquent\Model;
use Laracore\Exception\NoRepositoryToInstantiateException;
use Laracore\Repository\ModelRepository;
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
     * @return ModelRepository
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
    public function make(array $attributes = [], array $associatedRelations = [])
    {
        $model = $this->getRepository()->newModel($attributes);

        $this->addAssociatedRelations($model, $associatedRelations);

        return $this->repository->save($model);
    }

    /**
     * {@inheritdoc}
     */
    public function instantiateRepository()
    {
        throw new NoRepositoryToInstantiateException('Can\'t instantiate repository for ModelFactory. Make sure to set repository via setRepository method.');
    }

    /**
     * {@inheritdoc}
     */
    public function addAssociatedRelations(Model $model, array $associatedRelations, $save = false)
    {
        $relationRepository = $this->getRepository()->getRelationRepository();
        foreach ($associatedRelations as $relation => $value) {
            if (!$relationRepository->relationIsBelongsTo($model, $relation)) {
                throw new RelationNotBelongsToException('Only BelongsTo relations can be associated via addAssociatedRelations');
            }
            $relationRepository->associateRelation($model, $relation, $value);
        }
        if ($save) {
            $this->getRepository()->save($model);
        }
        return $model;
    }
}