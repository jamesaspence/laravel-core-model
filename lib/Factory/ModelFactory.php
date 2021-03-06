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
     * Sets whether or not to use mass assignment during creation of models.
     *
     * @var bool
     */
    protected $massAssign = false;

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
            $this->setRepository($this->instantiateRepository());
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
        $this->getRepository()->setModel($className);
    }

    /**
     * {@inheritdoc}
     */
    public function make(array $attributes = [], array $associatedRelations = [])
    {
        $repository = $this->getRepository();

        $model = $repository->newModel();

        if ($this->massAssign) {
            $repository->fill($model, $attributes);
        } else {
            foreach ($attributes as $key => $value) {
                $repository->setAttribute($model, $key, $value);
            }
        }

        $this->addAssociatedRelations($model, $associatedRelations);

        return $this->getRepository()->save($model);
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