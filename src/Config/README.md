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

## Load multiple config resource at once
This the most common and direct way to access to all you configuration resources.
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

## How it works

Each config pass through 1 or 2 handlers, a Loader and a Processor and should terminate by an array.

Loaders are mean to handle how we retrieve the configuration content which is useful when comes to files. Peak\Config comes with 3 loaders that handle most common cases:

 - ```DefaultLoader``` use file_get_contents()
 - ```PhpLoader``` use include(), the file must return an array
 - ```TextLoader``` use fread()

Processors are mean to handle how to process configuration data to an array. Peak\Config comes with 6 processors:

 - ```ArrayProcessor```
 - ```CallableProcessor``` closure and callable
 - ```CollectionProcessor``` Peak\Common\Collection
 - ```IniProcessor``` Supported advanced ini with dot notation
 - ```JsonProcessor```
 - ```YamlProcessor``` You will need symfony/yaml for this one
 
## Adding or changing a file handlers

```ConfigFile``` use the file handlers to determine how to load and process a file based on its extension. 

For example a php file will use the ```PhpLoader``` and the ```ArrayProcessor```. 

Default handlers are used by default and are stored in ```DefaultFilesHandlers```.
You can add/override file handlers easily with ```FilesHandlers::set()``` and ```FileHandlers::override()```

```php
// adding a new handler (if handler already exists, it will be overrided)
FilesHandlers::set(
    'xml',
    MyLoader::class,
    MyProcessor::class,
);

// adding/replacing multiple handlers
FilesHandlers::override([
    'xml' => [
        'loader' => MyLoader::class,
        'processor' => MyProcessor::class,
    ],
    'json' => [
        // ...
    ], 
    // ...
]);
```

## Caching complex configuration
Processing complex multiple configurations can be costly and if they rarely change, you might want to cache the result instead.

```php
$cc = new ConfigCache('/path/to/cache');

$cache_id = 'my-configuration-id';

if ($cc->isExpired($cache_id)) {
    $data = (new \Peak\Config\ConfigLoader([
        // files and stuff
    ]))->asCollection();
    
    $cc->set(
        $cache_id, 
        $data, 
        3600 // ttl in seconds
    );
} else {
    $data = $cc->get($cache_id);
}
```

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


