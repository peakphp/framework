# Peak\Config
#### Manage multiple configuration resources like a pro!
This component manage configuration of various format and source and merge them recursivly into one configuration collection. 


## Installation outside framework

```
$ composer require peak/config
```

## Load multiple config resources at once
```ConfigFactory``` is the most easiest and direct way to access to all you configuration resources. 
```php
$configFactory = new ConfigFactory();
$config = $configFactory->loadResources([
    'config/app.php',
    'config/app.dev.php',
    'config/database.yml',
    'config/widget.json',
]);
```

All configurations are merged into one. Existing configuration keys are overwritten by later definitions:
Example: 
- ```config/app.php``` contains ```array('foo' => 'bar')``` 
- ```config/app.dev.php``` contain ```array('foo' => 'bar2')```
- the final value of ```foo``` will be ```bar2```

Method *loadResources()* accept an array of resources and return an instance of *Peak\Config\Config*. It support types:

 - *String*, which are treated like filepath and processed internally by *FileStream*.
    Extensions supported by default: PHP array, Json, Text, Env, Ini, Yaml, Xml
 - *Arrays*
 - *Callable*, like closure or invokable classes
 - *Collection* instance
 - *Config* instance
 - *stdClass* instance
 - *StreamInterface* instance

## Loading and processing data with Stream.

Under the hood, ```ConfigFactory``` will determine the best way to load and process your config by using Stream underneath. 
You can also be specific by pushing a stream to *ConfigFactory::loadResource*.

```php
$configFactory = new ConfigFactory();
$config = $configFactory->loadResources([
    new JsonStream('{"foo": "bar"}'),
    new DataStream(["foo" => "bar2"], new ArrayProcessor()) // same as JsonStream
    new FileStream('myfile.json', new FileHandlers()),
    // ...    
]);
```

*Loaders* are mean to handle how we retrieve the configuration content which is useful when comes to files. Peak\Config comes with 3 loaders that handle most common cases:

 - ```DefaultLoader``` use file_get_contents()
 - ```PhpLoader``` use include(), the file must return an array
 - ```TextLoader``` use fread()

*Processors* are mean to handle how to process configuration data to an array. Peak\Config comes with 6 processors:

 - ```ArrayProcessor``` When resource is already an array
 - ```StdClassProcessor``` stdClass instance
 - ```CallableProcessor``` Closure and callable
 - ```CollectionProcessor``` Peak\Common\Collection
 - ```IniProcessor``` Parse ini data. Support advanced dot notation
 - ```JsonProcessor``` Parse json data
 - ```YamlProcessor``` Parse yaml data. symfony/yaml required for this one
 - ```XmlProcessor```  Parse xml data
 - ```EnvProcessor```  Parse env data
 
*Stream* are simply wrapper around loaders and processors. They are used internally by ```ConfigFactory``` to load resources correctly.

 - ```DataStream```
 - ```FileStream```
 - ```ConfigStream```
 - ```JsonStream```
 - ```XmlStream```

You can also create your own and/or used them directly load anything to an array:

```php
$dataStream = new DataStream('custom content', new MyCustomProcessor());
$array = $dataStream->get();
```

 
## Adding or changing a file handlers

```FileHandlers``` determine how to load and process a file based on its extension. 

For example a php file will use the ```PhpLoader``` and the ```ArrayProcessor```. 

You can add/override file handlers easily with by passing definitions to the constructor or via method ```set()```

```php
// create a file handlers and add a new handler (if handler already exists, it will be overrided)
$fileHandlers = new FileHandlers();
$fileHandlers->set(
    'xml',
    MyLoader::class,
    MyProcessor::class,
);

// and finally, tell the configuration factory to use your fileHandlers 
$configFactory = new ConfigFactory();
$configFactory->setFilesHandlers($fileHandlers);
$config = $configFactory->loadResources([
    //...
]);
```

## Caching complex configuration
Processing complex multiple configurations can be costly and if they rarely change, you might want to cache the result instead.

Using ```ConfigCacheFactory``` is the most easy way to handle configuration cache the same way you use ```ConfigFactory```:

```php
$ccFactory = new ConfigCacheFactory(
    new ConfigFactory(),
    new FileCache(__DIR__)
);

// load resources or load a cache version of processed configurations
$config = $ccFactory->loadResources('my-conf-id', 3600, [
    'path/to/your/config1.php',
    'path/to/your/config2.php',
]);
```
Or manually with ConfigCache and ConfigFactory:

```php
$configCache = new FileCache('/path/to/cache');

$cacheId = 'my-configuration-id';

if ($configCache->isExpired($cacheId)) {
    $configFactory = new ConfigFactory();
    $config = $configFactory->loadResources([
        'config/app.php',
        'config/app.dev.php',
        'config/database.yml',
        'config/widget.json',
    ]);
    
    $configCache->set(
        $cacheId, 
        $config, 
        3600 // ttl in seconds
    );
} else {
    $config = $configCache->get($cacheId);
}
```
