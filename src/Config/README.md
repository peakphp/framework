# Peak\Config
#### Manage multiple configuration resources like a pro!
This component manage configuration of various format and source and merge them recursivly into one configuration collection 


## Installation outside framework

```
$ composer require peak/config
```

## Supported formats

 - PHP Array
 - Json
 - Text
 - Ini
 - Yaml

## Quick start

```php
$cl = new ConfigLoader([
    'config/app.php',
    'config/app.dev.php',
    'config/database.yml',
    'config/widget.json',
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

## Loaders

Loaders are mean to handle how we retrieve the configuration content. Peak\Config comes with 3 loaders that handle most common cases:

 - ```DefaultLoader``` use file_get_contents()
 - ```PhpLoader``` use include(), the file must return an array
 - ```TextLoader``` use fread()
 
## Processors

Processors are mean for how we handle configuration content. Peak\Config comes with 6 processors:

 - ```ArrayProcessor```
 - ```CallableProcessor``` closure and callable
 - ```CollectionProcessor``` Peak\Common\Collection
 - ```IniProcessor``` Supported advanced ini with dot notation
 - ```JsonProcessor```
 - ```YamlProcessor``` You will need symfony/yaml for this one

## Extends

Create your own configuration loader and processor.

To use a custom loader/processor:

```php
// example 1
$content = (new ConfigFile(
    'cumstom.csv', 
    new DefaultLoader(), 
    new MyCustomProcessor()
)->get();

// example 2
$content = (new ConfigFile(
    'cumstom.file', 
    new CustomLoader(), 
    new ArrayProcessor()
)->get();

// example 3
$content = (new ConfigData(
    '... misc data...', 
    new MyCustomProcessor()
)->get();

// example 4 using config loader
$coll = (new ConfigLoader([
    'file1.php',
    new ConfigFile('cumstom.file', new CustomLoader(), new ArrayProcessor())
]))->asCollection();
```


