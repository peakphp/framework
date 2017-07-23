# Peak\Di
### PSR-11 Dependency Injection Container
This component allows you to standardize and centralize the way objects are constructed in your application. 
Implements [PSR-11](http://www.php-fig.org/psr/psr-11/)

## Installation outside framework

```
$ composer require peakphp/di
```

## Basic usage

### With Autowiring

No configuration needed. Just type-hint your constructor parameters and the container can guess which dependencies to inject.

```PHP
class Bar {}
class Foo
{
    public $bar;

    public function __construct(Bar $bar, $id = null, $alias = null)
    {
        $this->bar = $bar;
    }
}

$container = new Container;
$foo = $container->create(Foo::class);
```
In example above, a new ``Bar`` instance is created automatically each time ```Foo``` is created. This mechanism rely on ```Reflection``` to resolve objects dependencies.

### How method create() work
The method create() will help you to instantiate objects. It is important to understand that enabling/disabling autowiring affect how this method create objects.
Autowiring is enabled by default. To change this, use ```disableAutowiring()```.

Under the hood, ```create()``` go through those steps in order:

When Autowiring is enabled :
 - Check constructor type-hinted argument(s) using Reflection
 - Check for $explicit definition(s) to overload/guide the resolver
 - If no $explicit, look for a stored instance in the container, or instantiate a new one
   
When Autowiring is disabled:
 - Check for $explicit definition(s) to overload/guide the resolver.
 - If no $explicit, look for a matching definition and resolve it.


#### Parameters
```PHP
create(string $class [, array $args = [] [, mixed $explicit = null ]]])
```

#### ```$class``` 

Represent the class string name you want to create.

#### ```$args```

Represent other(s) non-object parameters if apply (or arguments if you prefer).

```PHP
class Foo {
    public function __construct(Bar $bar, $id = null, $desc = null) {
        $this->bar = $bar;
    }
}

$foo = $container->create(Foo::class, [
    12, // $id
    'FooBar' // $desc
]);
```

#### ```$explicit```

Because autowiring is not always able to resolve an interface, you need to specify how the container should resolve it.

Also, because this parameters can be also used to bypass a definition and/or a stored instance, you should only use it when you have no other choice. A better choice would be to rethink which object need to be stored or disable autowiring and use bind definition to control more precisely your objects creations.

```PHP
interface InterfaceA {}
class A implements InterfaceA {}
class B implements InterfaceA {}

class Foo {
    public function __construct(InterfaceA $a) {
        $this->a = $a;
    }
}

// throw an exception, there is no InterfaceA stored in container
$foo = $container->create(Foo::class);

// by adding class A instance, the container is now able to resolve Foo correctly
$container->add(new A);
$foo = $container->create(Foo::class);

// now we add another class that implement InterfaceA so you
// need to specify which one to use, otherwise, it will throw an exception
$container->add(new B);
$foo = $container->create(Foo::class, [], [
    InterfaceA::class => A::class
]);

```


### Reuse a class instance by storing it in the container with ```add()```
By default, method create() will always look for stored instance of Bar before creating a new one.

```PHP
$bar = new Bar();
$bar->name = "John Bar";

$container->add($bar);

$foo1 = $container->create(Foo::class);
$foo2 = $container->create(Foo::class);
```

In example above, ``$foo1`` and ``$foo2`` will have the same instance of ``Bar``.

```PHP
echo $foo1->bar->name; //output: John Bar
echo $foo2->bar->name; //output: John Bar
```

### Get a stored object instance with ```get()```

```PHP
$container->add(new Monolog\Logger);
$logger = $container->get(Monolog\Logger::class);
```

### Use alias for class name

```PHP
$container->add(new Monolog\Handler\StreamHandler(), 'LogStream');
$stream = $container->get('LogStream');
```

### Call an object method with ```call()```
You can also resolve dependencies of a object method with```call()```. It work like method ```create()``` except for the first parameter which must be an array containing the object instance and the method string name. 

```PHP

class Events {
    public function method(Bar $bar, $alias = null) {
        //...
        return $bar;
    }
}

$events = new Events;
$bar = $container->call([
    $events,
    'method
]);
```

### Definitions (Autowiring disabled)

For small and medium projects, autowiring can do the job correctly, but as your project grow, you may want to have more control over your objects creations.

This can be done with methods:

- ```bind()```
- ```bindPrototype()```
- ```bindFactory()```

To use definitions with ```create()```, you need to disable autowiring which is enabled by default:


```PHP
$container->disableAutowiring();
```

 

### Singleton definition with ```bind()```
The default singleton definition binding is an object that is first created than stored and reused.

```PHP

$container->disableAutowiring();

$container->bind(Foo::class, new Foo);
$foo = $container->create(Foo::class);

$foo->bar = 'foo';

$other_foo = $container->create(Foo::class);
// $foo === $other_foo
```

### Factory definition with ```bindFactory()```
A factory definition accept a callable definition that is executed each time.

```PHP

$container-->bindFactory(Finger::class, function (Container $c, $args) {
    // do some stuff and then return a object
    return new Finger(
        new A, 
        'factory', 
        'bar'
    );
});

// create foo successfully
$foo = $container->create(Foo::class);
$foo2 = $container->create(Foo::class);
// $foo !== $foo2
```

### Prototype definition with ```bindFactory()```
Prototype accept various definitions and always try to return a new instance of dependencies. ```bindPrototype()``` will ignore stored instance(s) and definition(s) in the container.

```PHP

class Hand {}

class Arm {
    public function __construct(Hand $hand) {
        // ...
    }
}

class Chest {
    public function __construct(Arm $arm, $argv = null) {
        // ...
    }
}

$container->bindPrototype(Chest::class, [
    Chest::class,
    Arm::class => [
        Arm::class,
        Hand::class,
    ],
    'bar',
]);

$chest = $container->create(Chest::class);
// $chest will always contain a new instance of Arm and Arm will 
// always contains a new instance of Hand and so on. 
```

### Definitions type
There is many way you can declare definition. Here a list of accepted definition for each one:

- ```bind()``` : callable, classname string, object, array of definition
- ```bindFactory()``` : callable only
- ```bindPrototype()``` : classname string, array of definition

### How Array definition work
Array definition represent a powerfull way to describe and group how dependencies can be resolve for a definition. It also support nested definition.

Inside the array of definition, supported type are: callable, classname string, object and array of definition. 

The first item of the array always represent the class to instantiate, other represent constructor argument(s).

```PHP
class A {
    public function __construct(B $b, C $c, $id) {
        // ...
    }
}

class B {
    public function __construct(D $d, $name) {
        // ...
    }
}

class C {}
class D {}

$container->bind(A::class, [
    A::class, // represent the class to instantiate
    B::class => [ // nested definition for $b
        B::class, // represent the class to instantiate
        D::class, // $d
        'JohnDoe' // $name
    ],
    C::class, // $c
    123  // $id
]);

$a = $container->create(A::class);

// what php equivalent look like (without stored instance(s))
$a = new A(
    new B(
        new D,
        'johndoe'
    ),
    new C,
    123
);
```

In the example above, the main difference between ```bind()``` and php vanilla is that ```bind()``` will look for stored instance or definition to resolve dependency before creating a new instance. To reproduce the same behavior with php vanilla, bind() should be replaced by ```bindPrototype()```.  

A more concrete example would be something like:


```PHP
class Database {
    public function __construct(DatabaseConfiguration $config) {
        // ...
    }
}

// bind a singleton for database configuration
$container->bind(DatabaseConfiguration::class, function(ContainerInterface $c) {
    return new DatabaseConfiguration(
        'locahost', 
        'dbexample', 
        'root', 
        'root'
    );
});

$container->bind(Database::class, [
    Database::class, // represent the class to instantiate
    DatabaseConfiguration::class // will resolve DatabaseConfiguration::definition and return the same instance of DatabaseConfiguration each time
]);


$db = $container->create(Database::class);

```