# Peak\Config
This component manage configuration of various format and source and merge them recursivly into one configuration collection.

## Installation outside framework

```
$ composer require peak/config
```

## Getting started

```php
$cl = new ConfigLoader([
    'config/app.php',
    'config/app.dev.php',
    'config/database.yml',
    'config/widget.json',
    new ConfigFile('cumstom.csv', new DefaultLoader(), new MyCustomProcessor()),
    new ConfigData('{"foo": "bar2", "bar" : "foo"}', new JsonProcessor()),
]);

// plain array
$config = $cl->asArray();

// plain stdClass
$config = $cl->asObject();

// collection
$config = $cl->asCollection();

// dot notation collection
$config = $cl->asDotNotationCollection();

// execute a closure on configuration collection to create something else
$config = $cl->asClosure(function(Collection $coll) {
    // do something and return something
});
```