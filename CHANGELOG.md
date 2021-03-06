VERSION 4.2.4
-------------
Release date: 2020-06-28

 - removed exception throwing in AbstractApplication::getProp() and hasProp()

VERSION 4.2.3
-------------
Release date: 2020-02-22

 - added method getConfigFile() to Config\Exception\FileNotFoundException and Config\Exception\FileNotReadableException
 - added method getPath() to Config\Exception\CachePathNotFoundException and Config\Exception\CachePathNotWritableException
 - added Blueprint\ConfigException and updated all Config exceptions accordingly
 - changed Backpack\Bedrock\AppRoutesMappers to sort alphabetically the routes by path name
 
VERSION 4.2.2
-------------
Release date: 2020-02-08

 - added Http\Exception\JsonBodyParserException and updated Http\Middleware\JsonBodyParserMiddleware to use it

VERSION 4.2.1
-------------
Release date: 2020-02-06

 - fixed bug in Collection\DotNotationCollection with methods get() and has() when the path end 
   with string instead array key name
 - fixed Backpack\Bedrock\AppRoutesMapper to ignore route group (Http\Request\PreRoute)
 - added the stack mapping to Backpack\Bedrock\AppRoutesMapper

VERSION 4.2.0
-------------
Release date: 2020-01-09

 - removed hard dependencies of symfony/console, symfony/process and symfony/yaml in composer.json and 
   moved them to suggest section

VERSION 4.1.2
-------------
Release date: 2020-01-06

 - improved exceptions for Collection\Structure
 - refactored function getShortClassName() from Common\helpers.php
 - Http\Request\Route will now also pass route arguments to PSR-7 request attributes

VERSION 4.1.1
-------------
Release date: 2019-10-28

 - improved exceptions for Collection\Structure
 - updated Kernel::VERSION number

VERSION 4.1.0
-------------
Release date: 2019-09-12

 - added group feature for pre-routing in to Bedrock\Http\Application
 - added Bedrock\Http\GroupManager and Http\Request\PreRoute
 - added methods getMatches() and pregMatch() to Http\Request\Route
 - removed deprecated methods Http\Request\RouteServerRequest getParam() and hasParam() in
   favor of getArg() and hasArg()
 - removed deprecated $param property for Request in favor of $args property
 - implemented interfaces ArrayAccess and Peak\Blueprint\Common\Arrayable in Http\Request\RouteArgs
 - deprecated method raw() of Http\Request\RouteArgs in favor of method toArray()
 
VERSION 4.0.1
-------------
Release date: 2019-08-13

 - renamed class Http\Request\RouteParameter to RouteArgs.
 - request arguments are now stored in the Request object under property $args.
   $param property from route request is still usable but will be remove in version 4.1.0 in favor of $args.
 - methods Http\Request\RouteServerRequest getParam() and hasParam() renamed to getArg() and hasArg().
   methods getParam() and hasParam() still can be used but will throw a user notice and will be remove
   in version 4.1.0 in favor of getArg() and hasArg()

VERSION 4.0.0
-------------
Release date: 2019-06-24

 - added View\Exception\VarNotFoundException
 - added Bedrock\Cli\Exception\InvalidCommandException
 - renamed method bind() to bindSingleton() in Di\Container for consistency and clarity
 - added methods bindSingletons(), bindPrototypes() and bindFactories() to Di\Container
 - moved Peak\View component outside the framework
 - moved Peak\Pipeline component outside the framework

VERSION 4.0.0-RC2
-----------------
Release date: 2019-05-24

 - added Http\Middleware\JsonBodyParserMiddleware and Http\Exception\BodyParserException
 - allow empty argument in Collection\Structure\AbstractStructure::create()
 - added Blueprint\Collection\Structure
 - added method getKeys() and static keys() to Collection\Structure\AbstractStructure
 - fixed edge case where singleton binding as string were not used properly in Di\Container and Di\Binding\Singleton
 - allow the usage of binding when autoWiring is on in Di\Container
 - allow resolution of interfaces dependencies via bindings in Di\InterfaceResolver
 - prevent infinite recursive string resolution in Di\Binding\Singleton, Di\Binding\Prototype and Di\ArrayDefinition
 - added Di\Exception\InfiniteLoopResolutionException
 - moved Di\AbstractBinding and Di\BindingInterface under Di\Binding folder
 - renamed method getKeys() to getStructureKeys() in Collection\Structure\AbstractStructure to be consistent
 - added method getHandlers() to Blueprint\Http\Stack and update Http\Stack and Http\Request\Route
 - removed deprecated components in Common: Reflection, Pagination, ClassFinder, TextUtils and TimeExpression
 - removed deprecated Backpack\BlackMagic
 - added Blueprint\View\Presentation
 - added Backpack\AppRoutesMapper
 - renamed method setClassName() to setAppClass() in Backpack\AppBuilder to be consistent
 - renamed Backpack\AppBuilder to Backpack\Bedrock\HttpAppBuilder. Kept Backpack\AppBuilder as class alias 
   for backward compatibility
 - renamed Backpack\ConfigLoader to Backpack\Config\HttpConfigLoader. Kept Backpack\ConfigLoader as class alias 
   for backward compatibility
 - added method addVars() and setViewClass() to Backpack\View\ViewBuilder
 
VERSION 4.0.0-RC1
-----------------
Release date: 2019-03-30

 - Http\Request\Exception\InvalidHandlerException return a more meaningful error message
 - updated namespace for functions
 - Backpack\View\ViewBuilder will throw an exception if Presentation is missing when building the view
 - renamed addMacro to setMacro in Common\Traits\Macro for consistency
 - updated project to phpunit 8.x
 - moved Backpack\View\Helper\BaseUrl to View\Helper\BaseUrl
 - fixed bug where MethodNotFoundException was not created correctly in Di\ClassInspector
 - renamed method, fixed methods visibilities and refined the word detection in Common\Traits\UpdateToCamelCase
 - fixed nested array settings key name in Common\PhpIni
 - fixed wrong implementation of JsonSerializable in Collection\PropertiesBag and Collection\Collection
 - fixed setting empty key behavior in Common\DotNotationCollection
 - fixed unreachable call_user_func() in Collection\Collection:__call 
 - added Config\Exception\ProcessorTypeException and Config\Processor\ConfigProcessor
 - removed Config\Stream\ConfigStream
 - fixed wrong condition order in Config\ConfigResolver
 - updated Config\Processor\YamlProcessor to handle edge case where yaml parse return a string
 - added $needle parameter for Di\ExplicitResolver closure
 - removed useless verification since $definition is typed in constructor in Di\Binding\Factory
 - removed useless verification since $object is in method typed in Di\Container::set()
 - fixed wrong condition order in Di\ArrayDefinition
 - added method getDefinitions() to Di\Container
 - prevent duplicate interfaces in Di\Container::addInterface()
 - remove unused Di\Exception\NotFoundException class
 - removed an always true condition in Http\Stack::process()

VERSION 4.0.0-BETA5
-------------------
Release date: 2019-02-20

 - added method returnResponse() to Http\Stack to allow re-handling the stack multiple times
 - Backpack\AppBuilder::setProps() will create a DotNotationCollection instead of PropertiesBag if array submitted.
 - fixed bug with trailing slashes for route "/"
 - added method bootstrap() to Blueprint\Application

VERSION 4.0.0-BETA4
-------------------
Release date: 2019-02-14

 - added Http\Request\RouteExpression and updated Http\Request\Route to use it
 - added default param to Http\Request\RouteServerRequest::getParam()
 - refactored Blueprint\Bedrock\Application into 3 new interfaces: 
   Blueprint\Bedrock\Application, Blueprint\Bedrock\HttpApplication and Blueprint\Bedrock\CliApplication
 - removed deprecated Bedrock\Application\Config
 - moved Bedrock\Application\Application to Bedrock\Http\Application
 - moved Bedrock\Application\AbstractBootstrapper to Bedrock\AbstractBootstrapper
 - added Bedrock\Cli\Application
 - added symfony/console and symfony/process to composer.json
 - added Bedrock\AbstractApplication to reduce repetitive code in Bedrock\Http\Application and Bedrock\Cli\Application

VERSION 4.0.0-BETA3
-------------------
Release date: 2019-02-12

 - removed trailing slash on request path in Http\Request\Route 
 - adding support of curly braces syntax for route parameters in Http\Request\Route 
 - added Http\Request\RouteParameter and updated Http\Request\Route to use it
 - added Http\Request\RouteServerRequest
 - added Common\Trait\MicroTime
 - refactored Common\Chrono
 - removed deprecated Common\ServiceLocator

VERSION 4.0.0-BETA2
---------------------------
Release date: 2019-02-05

 - raised minimum php version to 7.2
 - removed deprecated packages Validation and Rbac
 - removed package DebugBar
 - refactored exceptions in Di
 - added addToContainerAfterBuild() to Backpack\AppBuilder
 - added multiple set type methods in Collection\Structure\DataType
 - setProps() of Backpack\AppBuilder now accept an array or an instance of Blueprint\Collection\Dictionary
 - added Dictionary Blueprint to class Collection\DotNotationCollection
 - added method stackIfTrue() for conditional stacking in Bedrock\Application\Application 
 - removed render() return type, since the method could return a string or false in Blueprint\View\View
 - added method createStack() in Bedrock\Application\Application
 - removed deprecated Backpack\Application
 - renamed nullable() to null() for consistency in Collection\Structure\DataType

VERSION 4.0.0-BETA1
-------------------
Release date: 2019-01-24

 - first draft of v4