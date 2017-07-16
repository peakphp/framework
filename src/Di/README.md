# Peak Di
Dependency injection container.

## Usages

### With Autowiring

No configuration needed. Just type-hint your constructor parameters and the container can guess which dependencies to inject.

```PHP

class Foo
{
    public $bar;

    public function __construct(Bar $bar, $id = null, $alias)
    {
        $this->bar = $bar;
    }
}

$foo = $container->instantiate(Foo::class);
```
In example above, a new ``Bar`` instance will be instantiated automatically each time when creating ``Foo``

Passing other types of arguments:
```PHP
$foo = $container->instantiate(Foo::class, [
    12,
    'FooBar'
]);
```

#### Reuse a instance by storing it into the container with ```add()```

```PHP

$bar = new Bar();
$bar->name = "John Bar";

$container->add($bar);

$foo1 = $container->instantiate(Foo::class);
$foo2 = $container->instantiate(Foo::class);
```

In example above, ``$foo1`` and ``$foo2`` will have the same instance of ``Bar``.

```PHP
echo $foo1->bar->name; //output: John Bar
echo $foo2->bar->name; //output: John Bar
```

#### Without Autowiring
For small and medium projects, autowiring can do the job correctly, but as your project grow, you may want have more control over how your objects are instantiated and stored.
First you need to disable autowiring with disableAutowiring() You have to define how ```Foo``` object dependencies will be resolve. This can be be done with method ```setDefinitions()```

```PHP

$container->disableAutowiring();

// set definitions
$container->setDefinitions([
    Foo::class => function($container) {
        return [
            new Bar()
        ];
    }
]);

// create foo successfully
$foo = $container->instantiate(Foo::class);
//throw an exception since there is no definiton for Bar
$bar = $container->instantiate(Foo::class);
```