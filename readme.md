# Laravel Core Model

A repository, factory, and criteria layer for Eloquent models,
providing a convenient repository interface that still allows
fully-featured, eloquent usage. Allows convenient testing and 
dependency injection without sacrificing features or versatility.

## Requirements

- Laravel 5+ or Illuminate\Database 5+ (tested up to 5.2)
- PHP 5.6+

## Installation

    composer require jamesspence/core-model

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

###Criteria

Laracore now supports custom criteria. 
Criteria serve as a convenient way to 
build consistent queries.

####Examples

Setting a criteria:

```php
$criteria = new ModelCriteria();
$criteria->setClosure(function ($model) {
    return $model->where('name', '=', 'Test Testerton');
});
```

Applying a criteria:

```php
$repository = new ModelRepository(User::class);
$repository->addCriteria($criteria);

//This will search for both an email AND name
$user = $repository
    ->where('email', '=', 'test@test.test')
    ->first();
```

Criteria added to the repository will 
automatically be applied to the next
query, then discarded afterwards.
 
You can skip a criteria by calling `$criteria->skip()`.
You can undo this by setting `$criteria->skip(false);`.

Likewise, using `persist` and `perist(false)`, you can set
a criteria to not be cleared after the next query. To clear 
persistent criteria on the repository, you can call 
`$repository->clearCriteria(true)`. Calling `clearCriteria`
without the true flag will only clear non-persistent Criteria.

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
    //We just need to pass in our default model
    public function __construct($model = null, RelationInterface $repository = null)
    {
        if (is_null($model)) {
            $model = User::class;
        }
        parent::__construct($model, $repository);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultCriteria()
    {
        $criteriaBag = new CriteriaBag;
        
        //Creates a criteria that is not skipped, 
        //and is persistent.
        $criteria = new Criteria(function ($model) {
            return $model->where('name', '=', 'Test');
        }, false, true);
        
        $criteriaBag->addCriteria($criteria);
        
        return $criteria;
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