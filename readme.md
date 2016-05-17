# Laravel Core Model

Provides a repository and factory layer for Eloquent models,
providing a convenient repository interface that still allows
fully-featured, eloquent usage.

## Installation

    composer require jamesaspence/laravel-core-model

## ModelRepository

Laracore (Laravel Core Model) is very simple to use.
You can simply instantiate the ModelRepository.

### Basic Usage

```php
<?php

use Laracore\Repository\ModelRepository;


$repository = new ModelRepository(Book::class);

//Begin to code!
$books = $repository->all();
$book = $repository->find(123);
```

Several of the repository methods return
a query builder instance, allowing more advanced
queries.

```php
$books = $repository
    ->with('chapters')
    ->where('stuff', '=', 'things')
    ->get();

$books = $repository
    ->select('books.*')
    ->where('stuff', '=', 'things')
    ->first();
```

You can save models through the repository.

```php
$book = $repository->newModel(['stuff' => 'things']);
$repository->save($book);
```

You can fill and save as well.

```php
$book = $repository->newModel();
$repository->fillAndSave($model, ['stuff' => 'things']);
```

###Relations

Relations can also be saved through the repository. These methods map the model relation methods available.

```php
$relationRepository = $repository->getRelationRepository();
$relation->associateRelation($chapter, 'book', $book);

//Saves a relation
$relationRepository->save($chapter, 'book', $book);
```

## ModelFactory

The ModelFactory allows you to save models easily, without using the model themselves.

The ModelFactory needs a RepositoryInterface in order to work.

```php
$factory = new ModelFactory()
//We need to set a repository on the factory.
$factory->setRepository($repository);
```

Once we instantiate, we can create our models.

```php
//Let's make a book model.
$book = $factory->make(['stuff' => 'things']);
```
The `make` method allows us to create a model and save it right away.

We can also specify relations as part of the `make` method.
```php
//Assuming we already have a $chapter model
$book = $factory->make($attributes, [
    'chapters' => [
        $chapter
    ]
]);
```
This will create the model, with the relations, and save it.