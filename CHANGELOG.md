VERSION 2.27.0
--------------
Release Date: ?

VERSION 2.26.0
--------------
Release Date: 2018-02-24

 - simplified Peak\Pipelines\AbstractProcessor container support
 - added composer.json to Peak\Pipelines component

VERSION 2.25.0
--------------
Release Date: 2018-02-21

> Warning! This version contains breaking changes with 2.24.x and below [BC]
> Also, composer packages prefix has been renamed from peakphp/* to peak/*

 - deleted deprecated Peak\Bedrock\View\Helper\Debug
 - added Peak\Common\Session and removed Peak\Config\Session
 - updated Peak\Pipelines\AbstractProcessor to use InvalidPipeException and MissingPipeInterfaceException
 - added Peak\Pipelines\Exceptions\InvalidPipeException and Peak\Pipelines\Exceptions\MissingPipeInterfaceException
 - [BC] standardized configuration for Peak\Bedrock\Application\Bootstrap\Session
 - [BC] rebuilded completely Peak\Bedrock\View\Helper\DebugBar
 - added function catchOutput() in Peak\Common\helpers.php
 - [BC] renamed function getClassPath() to getClassFilePath() in Peak\Common\helpers.php
 - [BC] renamed function formatFileSize() to formatSize() in Peak\Common\helpers.php
 - added function interpolate() in Peak\Common\helpers.php
 - added Peak\Bedrock\Application\Exceptions\ InstanceNotFoundException and MissingContainerException
 - added new mode to Peak\Common\ExceptionLogger
 - added Peak\Bedrock\View\Exceptions\ EngineNotSetException and HelperNotFoundException

VERSION 2.24.0
--------------
Release Date: 2018-02-05

 - updated param $url of method redirectUrl() to make it optional when redirecting to home page
   in Peak\Bedrock\Controller\ActionController
 - added method setCache() to Peak\Bedrock\View\Header
 - added Peak\Common\CollectionFlattener
 - deleted deprecated application module in favor of parent/child action controller
 - added function isDev() in Peak\Bedrock\helpers.php
 - added a second param to detectEnvFile() in Peak\Bedrock\helpers.php for default fallback env, added detection 
   of .dev and chosen .prod file as first priority
 - removed application view path from Peak\Bedrock\View\Block::__construct()
 - added application view path to Peak\Bedrock\View::renderBlock()
 - added interfaces Peak\Common\Renderable, Peak\Common\Outputable and Peak\Common\Initializable
 - updated class Peak\Bedrock\View\Block to use Peak\Common\Interfaces\Renderable instead of Peak\Bedrock\View\RenderableInterface
 - deleted Peak\Bedrock\View\RenderableInterface
 - added function getClassPath() in Peak\Common\helpers.php
 
VERSION 2.23.2
--------------
Release Date: 2018-01-29

 - fixed another rare bug where filter_input(INPUT_SERVER) return nothing in function url() Peak\Bedrock\helpers.php

VERSION 2.23.1
--------------
Release Date: 2018-01-26

 - deleted deprecated Peak\Bedrock\View\Render\Partials, Virtual and VirtualLayouts
 - fixed missing argument in Peak\Bedrock\Controller\RedirectionController constructor

VERSION 2.23.0
--------------
Release Date: 2018-01-24

 - added class Peak\Config\Type\LogLoader
 - fixed a bug where Peak\Validation\DataSet::validate won't stop at first error rule
 - added support of session options from config in Peak\Bedrock\Application\Bootstrap\Session
 - fixed rare bug where filter_input(INPUT_SERVER, 'REQUEST_URI') return nothing in
   Peak\Routing\RequestServerURI (thanks to Mogwy)
 - added Peak\Bedrock\Application\Bootstrap\RedirectRoutes and Peak\Bedrock\Controller\RedirectionController to handle
   routes URLs redirections gracefully
 - added application config instance to Peak\Bedrock\Controller\ActionController constructor
 - added Peak\Bedrock\Application\Exceptions\MissingConfigException
 - updated Peak\Bedrock\Application\ConfigResolver to use MissingConfigException
 - renamed constant SVR_ABSPATH to ROOT_ABSPATH
 - deleted deprecated constant LIBRARY_ABSPATH
 - updated Peak\Bedrock\Controller\ChildController to check in $parent for unknown property instead of
   injecting instances in constructor
 - added Peak\Bedrock\View\Exceptions\BlockNotFoundException
 - added function fileExpired() in Peak\Common\helpers.php

VERSION 2.22.0
--------------
Release Date: 2017-12-06

> Warning! Breaking changes with 2.21.x and below

 - [BC] replaced old way to manage controller action params. 
   $params is now an instance Peak\Bedrock\Controller\ParamsCollection and $params_raw has been removed
 - bootstrap processes can now request the container in their constructors
 - cli commands can now request the container in their constructors
 - added support of object to Peak\Bedrock\View\Helper\Grid
 - updated method addRowDataAttr() and rowDataAttr() in Peak\Bedrock\View\Helper\Grid to support Closure
 - added renderable view block
 - moved logic from helper function phpinput to Peak\Common\PhpInput
 - added mode param to Peak\Common\ExceptionLogger
 - fixed a bug with getting cache content in Peak\Bedrock\View\Cache
 - updated Peak\Common\ExceptionLogger to support custom log content closure

VERSION 2.21.0
--------------
Release Date: 2017-11-03

 - added the ability to store closures in view variables and be used inside templates like a normal view helper
 - added method __call() to Peak\Bedrock\Controller\ChildActionController to check in parent controller for unknown methods
 - added Peak\Climber\Cron\CronApi
 - added Peak\Climber\Cron\CronBuilder
 - added Peak\Climber\Cron\Exceptions\CronBuilderException
 - added Peak\Climber\Cron\Exceptions\InvalidDatabaseConfigException
 - added method yesNoValid() to Peak\Climber\Cron\OptionFormat
 - added method raw() to Peak\Climber\Cron\CronEntity
 - renamed Peak\Climber\Cron\Cron to Peak\Climber\Cron\CronSystem and added static method connect()
 - added Peak\Bedrock\Application\Exceptions\NoRouteFoundException
 - added Peak\Bedrock\Application\Exceptions\ControllerNotFoundException

VERSION 2.20.1
--------------
Release Date: 2017-10-11

 - fixed missing app container in Peak\Bedrock\Application\Bootstrapper for climber

VERSION 2.20.0
--------------
Release Date: 2017-10-08

 - added static method isValid() and createFrom() to Peak\Common\TimeExpression
 - added the request uri exception message in Peak\Bedrock\Controller\FrontController
 - rounded time before outputting string in Peak\Common\TimeExpression
 - output milliseconds string if time is between 0 and 1 second in Peak\Common\TimeExpression
 - added method toClockString() in Peak\Common\TimeExpression
 - fixed bug in Peak\Common\TimeExpression::toString() with "30 day" to 1 month conversion
 - Peak\Bedrock\Application\Bootstrapper is now able to resolve dependencies for init* and per environment methods
 - added Peak\Routing\CustomRouteBuilder
 - updated Peak\Bedrock\Application\Bootstrap\ConfigCustomRoutes to use CustomRouteBuilder
 - added method push() to Peak\Common\Collection

VERSION 2.19.0
--------------
Release Date: 2017-08-19

 - added bzip2 compression option in Peak\Bedrock\View\Cache
 - fixed method blockEnd() to use correctly saveContent() in Peak\Bedrock\View\Cache
 - added Peak\Config\ConfigSoftLoader
 - added option for using ConfigSoftLoader with application config(s)
 - changed default application name
 - rewritten Peak\Common\TimeExpression to use DateInterval
 - fixed an error with 0|empty time expression in Peak\Common\TimeExpression
 - added the support of format HH:MM:SS to Peak\Common\TimeExpression
 - removed method toDate() in Peak\Common\TimeExpression
 - added flag for delaying the first execution for climber command cron:add
 
VERSION 2.18.1
--------------
Release Date: 2017-08-11

 - fixed missing property $use_cache in Peak\Bedrock\View\Cache

VERSION 2.18.0
--------------
Release Date: 2017-08-10

 - fixed a bug with query in Peak\Cli\Commands\ClimberCronDelCommand
 - removed methods getScriptFile() and getScriptPath() in Peak\Bedrock\View\Cache
 - added method setPath() and updated default path in Peak\Bedrock\View\Cache
 - added method createCachePath() in Peak\Bedrock\View\Cache and throw an exception if cannot create cache path
 - Peak\Bedrock\View\Render create cache object only when needed now and pass the cache path argument
 - added a new setting "auto_routing" that allow to disable "magic routing" and rely exclusively on 
   user pre-defined custom route(s)
 - fixed a bug in Peak\Bedrock\Controller\ActionController::dispatchActionParams() could not match action param(s)
   correctly in some situations
 - added Peak\Config\Type\IniLoader and removed deprecated classes Peak\Config\File\Ini and Peak\Config\Type\Json
 - added aliases for main application classes
 - removed deprecated Peak\Config\File
 - replace enableStrip() by trimSpaces() in Peak\Bedrock\View\Cache
 - added saveContent() in Peak\Bedrock\View\Cache to implements cache file compression

VERSION 2.17.0
--------------
Release Date: 2017-08-01

 - renamed Peak\Climber\Cron\Bootstrap to Peak\Climber\Cron\BootstrapDatabase
 - added default prefix and output a message when cron job failed in Peak\Climber\Cron\Executor
 - renamed Climber cron commands to avoid collision with user app cli commands names
 - added Peak\Climber\Bootstrap\ConfigCommands
 - added lock mechanism in Peak\Climber\Cron\Executor to prevent re-executing unfinished jobs.
 - added Peak\Config\Type\TxtLoader
 - added ms precision and formatting option for toString() in Peak\Common\TimeExpression
 - fixed bug in Peak\Climber\Cron\Executor where cron repeat count field was not updated
 - fixed bug in Peak\Common\TimeExpression::toString() when time is 0
 - updated function config() in Peak\Bedrock\helpers.php to support cli application config too
 - updated Peak\Climber\Commands\ClimberCronRunCommand to propagate current environment to cron running configuration

VERSION 2.16.0
--------------
Release Date: 2017-07-28

> Warning! Breaking changes with 2.15.x and below

 - [BC] replaced Peak\Di\ContainerInterface by Psr\Container\ContainerInterface
 - removed Peak\Common dependency for Peak\Di
 - added method resolve(), bind(), bindPrototype() and bindFactory() to Peak\Di\Container
 - [BC] container is now passed to Peak\Di\ExplicitResolver
 - [BC] rewrited completely the definition part for Peak\Di and removed old Peak\Di\ClassDefinitions
 - [BC] get() can throw a NotFoundException if id not found in Peak\Di\Container (PSR-11)
 - [BC] renamed method genCacheId() to generateId(), getCacheFile() to getFile(), deleteCache() to
   delete() and getCacheBlock() to getContent() in Peak\Bedrock\View\Cache
 - [BC] removed ability to start the cache when using isValidBlock() in Peak\Bedrock\View\Cache
 - added method blockStart() in Peak\Bedrock\View\Cache
 - fixed bug with function session() in Peak\Bedrock\helpers.php
 - added Peak\Climber\Application instance to the container when creating it

VERSION 2.15.0
--------------
Release Date: 2017-07-16

> Warning! Breaking changes with 2.14.x and below

 - added possibility to turn off autowiring for Peak\Di\Container
 - added Peak\Di\ClassDefinitions for handling dependencies when autowiring is disable
 - added method addDefinition(), setDefinitions() and hasDefinition() to Peak\Di\Container
 - upgraded Peak\Common(peakphp/common) and Peak\Di(peakphp/di) to components on packagist
 - [BC] method instantiate() renamed to create() in Peak\Di\Container
 - [BC] method instantiateAndStore() renamed to createAndStore() in Peak\Di\Container
 - [BC] method create() renamed to build() in Peak\Bedrock\Application
 - [BC] static method instantiate() renamed to create() in Peak\Bedrock\Application

VERSION 2.14.0
--------------
Release Date: 2017-07-12

 - added Peak\Common\TimeExpression
 - added an exception in Peak\Di\Container::add()
 - added cron job system for Peak\Climber component
 - added doctrine/dbal component
 - added symfony/process component

VERSION 2.13.0
--------------
Release Date: 2017-07-03

 - added callable support to Peak\Config\ConfigLoader
 - added setSuffix() and setPrefix() to Peak\Common\ClassFinder
 - moved illuminate/database and illuminate/events as default dependencies to peak default application
 - added symfony/console component
 - added method asClosure() in Peak\Config\ConfigLoader
 - added support of Yaml config for Peak\Config\ConfigLoader
 - container is now passed to the constructor instead of hard-coded in Peak\Bedrock\Application\Bootstrapper
 - added console application component Peak\Climber based on symfony/console component
 - updated functions __() and _e() to return text in case of not finding a valid instance of Peak\Common\Language
 
VERSION 2.12.0
--------------
Release Date: 2017-06-21

> Warning! Breaking changes with 2.11.x and below

 - deleted deprecated class Peak\Bedrock\View\Helper\Tagattrs
 - refactored Peak\Bedrock\View\Helper\Assets
 - [BC] moved Peak\Config\DotNotation to Peak\Common\DotNotationCollection
 - [BC] removed deprecated method have() in Peak\Common\DotNotationCollection
 - added Peak\Config\ConfigLoader component
 - added method asArray() and asObject() to Peak\Config\ConfigLoader
 - [BC] refactored Peak\Bedrock\Application\ConfigResolver with new ConfigLoader component and
   modified how application configuration(s) are setup and loaded
 - [BC] deleted Peak\Bedrock\Application\Config\Environment which is now useless with ConfigLoader
 - [BC] deleted Peak\Bedrock\Application\Config\FileLoader which is now useless with ConfigLoader
 - added method get() to Peak\Bedrock\Application\Config\AppTree
 - added file (exists|is writable) checks to log() in Peak\Common\ExceptionLogger
 
VERSION 2.11.0
--------------
Release Date: 2017-06-16

> Warning! Breaking changes with 2.10.x and below

 - [BC] renamed Peak\Bedrock\Controller\Front to Peak\Bedrock\Controller\FrontController
 - [BC] renamed Peak\Bedrock\Controller\Child to Peak\Bedrock\Controller\ChildActionController
 - [BC] renamed Peak\Bedrock\Controller\Action to Peak\Bedrock\Controller\ActionController
 - added an exception in Peak\Bedrock\View\Form\FormBuilder::control()
 - added Peak\Common\TextUtils move code from Peak\Bedrock\View\Helper\Text there
 - added function formatFileSize() to Peak\Common\helpers.php
 - deleted deprecated class Peak\Common\Spl\Fileinfo and Peak\Common\Spl\Dirinfo
 - fixed bug with array of punctuations in Peak\Validation\Rules\Alpha
 - added property $child to Peak\Bedrock\Controller\ParentController to access to loaded child action instance
 - added property $action_ns to Peak\Bedrock\Controller\ParentController to customize which namespace to use
   for loading child action class
 - fixed a bug with release() and $content in Peak\Bedrock\View\Header
 - added method has() to Peak\Bedrock\View\Header

VERSION 2.10.0
--------------
Release Date: 2017-06-14

 - Application::setContainer() now return the container instance
 - method validate() will now use current form data if nothing specified in Peak\Bedrock\View\Form\FormBuilder
 - added method getData() and getErrors() in Peak\Bedrock\View\Form\Form
 - added method renderError() in Peak\Bedrock\View\Form\FormControl
 - fixed missing $error arguments in Peak\Bedrock\View\Form\Element constructor
 - changed trigger_error to an Exception for cloning Peak\Common\Registry
 - changed trigger_error to an Exception for unknown helper Peak\Bedrock\View
 - fixed variable name bug in Peak\Events\Dispatcher::detach()
 - refactored Peak\Common\Annotations
 - added method has() and marked method have() as deprecated in Peak\Config\DotNotation
 - fixed bug where "error" class could be duplicated when generating attributes in Peak\Bedrock\View\Form\Element
 - fixed bug where custom $base_uri was not spread correctly to Request in Peak\Bedrock\Application\Routing

VERSION 2.9.0
-------------
Release Date: 2017-06-05

 - added Peak\Common\Traits\ArrayMergeRecursiveDistinct and updated 
   Peak\Common\Collection, Peak\Config\DotNotation and Peak\Config\File\Ini accordingly
 - removed $default_processes in Peak\Bedrock\Application\Bootstrapper moved
   this responsibility into the application bootstrapper.
 - passing correctly param(s) to helpers in Peak\Bedrock\View
 - rewritten helper() method with ClassFinder in Peak\Bedrock\View
 - fixed unused variable in Peak\Bedrock\View\Cache
 - added function getPhinxMigrateEnv() in Peak\Bedrock\helpers.php

VERSION 2.8.0
-------------
Release Date: 2017-06-03
 
 - replaced global $_SERVER by filter_var() + getenv() in Peak\Bedrock\Application\ConfigResolver
 - replaced global $_SERVER by filter_input_array() for url() function
 - replaced global $_SERVER by filter_var() + getenv() for relativePath() and relativeBasepath() functions
 - replaced global $_SERVER by filter_var() in Peak\Routing\RequestServerURI
 - fixed bug with $this and static method conf() in Peak\Bedrock\Application
 - function isEnv() now accept also an array of env as argument
 - added Rbac component

VERSION 2.7.0
-------------
Release Date: 2017-05-28

 - added classes Peak\Bedrock\Controller\ParentController and Peak\Bedrock\Controller\Child to enabled
   controller to have one class per action instead of one method per action
 - added method __callStatic() to Peak\Validation\Rule
 - added method getErrors() to Peak\Bedrock\View\Form\FormValidation and Peak\Bedrock\View\Form\FormBuilder
 - passed to protected visibility method createDataSet() of Peak\Bedrock\View\Form\FormValidation
 - added methods setData() and setErrors() to Peak\Bedrock\View\Form\FormBuilder
 - Peak\Common\Paginator throw an exception instead of a notice for current page that is out of range 
 - fixed hardcoded app namespace in PeakBedrock\View and Peak\Bedrock\Controller\ParentController

VERSION 2.6.0
-------------
Release Date: 2017-05-19

 - removed methods getName(), getAction() and getActions() in Peak\Bedrock\Controller\Action
 - added method hasAlias() to Peak\Di\Container
 - fixed a bug with Application::get() and instance alias name
 - fixed an undefined variable for Peak\Events\Dispatcher::handleCallback()
 - added method callAction() to Peak\Bedrock\Controller\Action
 - added Peak\Common\PaginatorBuilder
 - added second argument Peak\Di\Container::add() for alias

VERSION 2.5.0
-------------
Release Date: 2017-05-16

 - added an exception with invalid callback in Peak\Di\ClassResolver::resolve()
 - added method is() to Peak\Routing\Route
 - added closure support to phpinput() in Peak\Common\helpers.php
 - added function detectEnvFile() in Peak\Bedrock\helpers.php
 - added Pipelines component
 - removed deprecated class Peak\Bedrock\Controller\Helper\Request

VERSION 2.4.0
-------------
Release Date: 2017-05-02

> Warning! Breaking changes with 2.3.x. and below

 - [BC] removed deprecated internal controllers notion
 - added method map() and toObject in Peak\Common\Collection
 - method jsonSerialize() now accept json_encode params in Peak\Common\Collection
 - [BC] renamed method addInstance() to add() in Peak\Di\Container
 - [BC] renamed method deleteInstance() to delete() in Peak\Di\Container
 - fixed bug with createAbility() in Peak\Doorman\Manager
 - added method addItself() to Peak\Di\Container

VERSION 2.3.0
-------------
Release Date: 2017-04-20

> Warning! Breaking changes with 2.2.x. 

 - [BC] renamed method getInstance() to get() in Peak\Di\Container
 - [BC] renamed method hasInstance() to has() in Peak\Di\Container
 - added method get() and has() to Peak\Di\ContainerInterface
 - [BC] moved Peak\Database to Peak\Providers\Laravel\Database
 - [BC] rewritten Peak\Lang to Peak\Common\Language
 - [BC] updated function _e() and __() in Peak\Bedrock\helpers to reflect changes in Peak\Common\Language
 - added Peak\Common\Traits\LoadArrayFiles

VERSION 2.2.0
-------------
Release Date: 2017-04-06

> Warning! Breaking changes with 2.1.x. 

 - [BC] moved Peak\View component to Peak\Bedrock\View
 - added alias notion to Peak\Di\Container

VERSION 2.1.5
--------------
Release Date: 2017-03-22

- added Peak\Di\Container::call() for resolving a class method dependencies
- updated Peak\Di\ClassInspector and Peak\Di\ClassResolver to support Peak\Di\Container::call()
- fixed bug with shortClassName() in Peak\Common\helpers.php by removing Object type

VERSION 2.1.4
--------------
Release Date: 2017-03-21

 - fixed container wrong classname in Peak\View\Render\Layouts::render() and Peak\View\Render\Partials::render()

VERSION 2.1.3
--------------
Release Date: 2017-03-20

 - Hotfix for Exception in Peak\View

VERSION 2.1.2
--------------
Release Date: 2017-03-20

 - deleted deprecated Peak\Exception
 - updated exception dependency of many classes
 - added Peak\Common\DataException
 - moved framework version number from Peak\Bedrock\Application to Peak\Bedrock\Application\Kernel
 - added HHVM test to Travis CI build

VERSION 2.1.1
--------------
Release Date: 2017-03-17

 - added default process Peak\Bedrock\Application\Bootstrap\Session
 - modified classes in Peak\Bedrock\Application\Bootstrap to support Di

VERSION 2.1.0
--------------
Release Date: 2017-03-16

> Warning! Breaking changes with 2.0.x

 - [BC] replaced old Registry by Dependencies Injection Container
 - [BC] moved Application and Controller components under Bedrock folder
 - added method instantiateAndStore() to Peak\Di\Container
 - [BC] moved Application logic to Bedrock\Application\Kernel
 - [BC] moved code in Core.php to Bedrock\helpers.php
 - [BC] added framework version constant to Peak\Bedrock\Application and removed PEAK_VERSION global constant
 - code refactoring for PSR-2
 - added Common\ExceptionLogger

VERSION 2.0.12
--------------
Release Date: 2017-03-11

 - code refactoring for PSR-2
 - fixed exception namespace in Peak\Common\Collection
 - added Peak\Di\ContainerInterface

VERSION 2.0.11
--------------
Release Date: 2017-03-08

 - moved Annotations, Chrono, Collection, Paginator to Common folder
 - moved general functions from Core.php to Common\helpers.php
 - code refactoring for PSR-2

VERSION 2.0.10
--------------
Release Date: 2017-03-05

 - added Validation component
 - removed Filters component favor of the new Validation component
 - added FormValidation and FormDataSet wrapper to Form component
 - code refactoring for PSR-2

VERSION 2.0.9
-------------
Release Date: 2017-02-21

 - added Doorman component for managing users/groups permission(s) like linux file permissions
 - separated default processes from app processes in Peak\Application\Bootstrapper
 - added property $boot_methods_prefix in Peak\Application\Bootstrapper

VERSION 2.0.8
-------------
Release Date: 2017-02-17

 - added support of custom form controls in Peak\View\Form\Form
 - fixed double slashes in url()
 - removed deprecated method iniVar() in Peak\View

VERSION 2.0.7
-------------
Release Date: 2017-02-14

 - updated reference of baseUrl to url()
 - deleted deprecated method baseUrl() in Peak\View\Render

VERSION 2.0.6
-------------
Release Date: 2017-02-14

 - removed deprecated Peak\Controller\Helper
 - removed deprecated helper notion in Peak\Controller\Action
 - cleaning code for PSR-1 and PSR-2
 - deleted method url() in Peak\View\Render
 - marked baseUrl() in Peak\View\Render as deprecated

VERSION 2.0.5
-------------
Release Date: 2017-02-12

 - fixed params typo error in Peak\Controller\Action

VERSION 2.0.4
-------------
Release Date: 2017-02-12

 - simplified config custom routes definition syntax
 - fixed duplication of html id with element label Peak\View\Form component
 - Peak\Controller\Action property $params become $params_raw and $params_assoc become $params
 - removed method params() in Peak\Controller\Action. Use $params_raw or $params instead

VERSION 2.0.3
-------------
Release Date: 2017-02-06

 - added Dependency Injection Component

VERSION 2.0.2
-------------
Release Date: 2017-01-29

 - fixed path in View\Cache
 - added function showAllErrors() in Core
 - updated composer.json to use the latest 5.4.x version of laravel database
 - added Config\Session class
 - added function session() to Core

VERSION 2.0.1
-------------
Release Date: 2017-01-28

 - update to laravel 5.4.*
 - removed duplicated dependencies with laravel in composer.json
 - added function url() to Core

VERSION 2.0.0
-------------
Release Date: 2017-01-27

> Warning! Breaking changes in 2.0.x
If you want want to support an old application, please use version 1.x
This version is incompatible with 1.0.0 because most components have  
been rewritten, splitted and/or reorganised.
 
 - namespace migration
 - multiples fix due to namespace migration
 - switched to laravel database powerful components
 - added database wrapper around capsule manager
 - removed zend db vendor
 - removed zend reflection vendor
 - added view helper class to inject view object into view helper
 - added per environment functionality to application bootstrap
 - added new routing components
 - added new paginator component
 - added events dispatcher
 - added new collection component
 - most code inside Core have been moved to new application components.
 - Core regroup global and app functions now
 - Config now inherited from Collection
 - removed unused spl autoload functions
 - removed deprecated Xml and Dispatcher class
 - removed deprecated Codegen class
 - removed deprecated Zreflection class
 - removed deprecated Router class
 - removed deprecated View\Theme
 - removed all models classes (replaced by laravel eloquent)