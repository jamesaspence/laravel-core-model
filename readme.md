# Laravel Core Model

A repository, factory, and criteria layer for Eloquent models,
providing a convenient repository interface that still allows
fully-featured, eloquent usage. Allows convenient testing and 
dependency injection without sacrificing features or versatility.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
  - ["Magic" Methods](#magic-methods)
  - [Relations](#relations)
  - [Dependency Injection](#dependency-injection)
  - [Model Factories](#model-factories)
  - [Inheritance](#inheritance)
- [Future Plans](#future-plans)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Requirements

- Laravel 5+ or Illuminate\Database 5+ (tested up to 5.2)
- PHP 5.6+

## Installation

    composer require jamesaspence/core-model

## Usage

Laracore (Laravel Core Model) is very simple to use. 
It works exactly the same as the Eloquent models 
you're already used to. This means you can use all 
the model queries you're already used to.

```php
//First we instantiate the repository
$repository = new ModelRepository();
 
//We pass in the reference to the model class
//so we can query it
$repository->setModel(User::class);
 
//Now we use the repository to find a model
$user = $repository->find(1);
 
//Let's save some new attributes
$repository->fill($user, [
    'name' => 'Test Testerton'
]);
 
//Finally, let's save!!
$repository->save($model);
```

More advanced queries are allowed as well, 
including custom queries. Assuming the same
repository as before:

```php
//Let's query based on email AND name
$user = $repository
    ->query()
    ->where('name', '=', 'Test Testerton')
    ->where('email', '=', 'test@test.test')
    ->first();
```

The repository's methods map to the model's 
methods, allowing, in the above example, a 
return of a query builder. This means we don't 
lose any of the features we've come to love from 
Eloquent.

### "Magic" Methods

Laracore's repositories support the calling of
magic methods, such as local scope queries. For
example, consider the following code:
```php
$model = User::active()->get();
```

You do not need to define a custom repository
with this method hardcoded.
```php
$repository = new ModelRepository(User::class);
$model = $repository->active()
    ->get();
```

Instead, we can call our scope queries and other
magic methods directly on the repository. The
repository will delegate them on to the model
class.

Our magic method handling also listens for a model
instance being the first argument of a magic method
called via this repository. If the first argument is
an instance of a model, it will instead call the method
on the model instance itself! See the below example:

```php
//This
$model = new User();
$repository->doThing($model, $stuff, $things);

//Is equivalent to this
$model->doThing($stuff, $things);
```

This is meant to catch missed repository methods that we would
want implemented. If this causes issues, feel free to reach out
via the issues on this repository!

### Relations

Laracore also allows retrieval of relations.

```php
$user = $repository
    ->with(['tokens', 'profile'])
    ->find(1);
    
//Let's also load a relation with an existing model.
$repository->load($existingUser, 'comments');
```

`ModelRepository` classes have a `RelationRepository` 
set which allows even more advanced relation settings, 
such as `sync` and `associate`. 

```php
//You can also pass in the class definition into the constructor.
$profileRepository = new ModelRepository(Profile::class);
 
$profile = $profileRepository->newModel(['stuff' => 'things']);
 
//$repository is still set for User::class here
$user = $repository->find(1);
 
//Assuming a BelongsTo relation named profile() 
//on User, let's associate it!
$repository
    ->getRelationRepository()
    ->associateRelation($user, 'profile', $profile);
 
//Dont forget to save!
$repository->save($user);
 
//Assuming comment IDs...
$commentIds = [1, 2, 3];
 
//Let's sync them to a comments relation!
$repository
    ->getRelationRepository()
    ->sync($user, 'comments', $commentIds);
```

All relation methods should be represented as well, 
allowing versatile use.

### Dependency Injection

One of the best aspects of this library is the 
ability to dependency inject your database access,
rather than using static methods.

```php
// Rather than doing this... bad!!
public function badControllerMethod()
{
    $user = User::find(1);
}
 
//We can do this! Good!
public function goodControllerMethod(ModelRepository $repository)
{
    $repository->setModel(User::class);
    
    $user = $repository->find(1);
}
```

This allows easy dependency injection, which in 
turn makes it very easy to isolate dependencies 
for testing.

### Model Factories
Want to create models without using `new Model` all over your code? `ModelFactory` is here to help!

```php
$factory = new ModelFactory();
 
//We need to pass in a ModelRepository 
//to be able to save
$factory->setRepository(new ModelRepository(User::class));
 
$user = $factory->make([
    'name' => 'Test Testerton'
]);
```
This will save the model with the attributes specified.

You can also use the `ModelFactory` to save `BelongsTo` 
relations:

```php
$user = $factory->make([
    'name' => 'Test Testerton'
], [
    'profile' => $profile
]);
```

### Inheritance

Another nice feature is the ability to extend 
these classes at will. You can continue to use 
`ModelRepository` on its own, but if you prefer, 
you can extend the repositories and factories yourself.

Here, we'll extend `ModelRepository` so we don't have to 
set the model every time. We'll also make it so default 
criteria are set on the repository.

```php
class UserRepository extends ModelRepository 
{
   /**
    * {@inheritdoc}
    */
    public function getDefaultModel()
    {
        //We just need to pass in our default model
        return User::class;
    }
}
```

Then, we can use this without setting a model! 
No `setModel` required!

```php
public function controllerMethod(UserRepository $repository)
{
    $user = $repository->find(1);
}
```

This will perform the following query (if using MySQL):
```
SELECT * FROM `users` WHERE `name` = ? AND `id` = ?
```
with the two bound parameters of 'Test' and '1'.

## Future Plans
Short term, the plan is to keep this library compatible with major 
versions of Laravel > 5. That means testing for new versions and 
adding new methods that exist in newer versions.

I would love to add non-eloquent support to this repository. 
The plan is to add both raw query as well as Doctrine repositories, 
but that isn't coming quite yet.

Long-term plans are a little more unclear. After non-eloquent support,
I will probably decide on my next feature to implement. If you have any
ideas, I would love to hear them!