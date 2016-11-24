VERSION 1.0.0
-------------
Release Date: ?

 - added the good http header for Json Render Engine
 - fixed a bug in Peak_Controller_Action when disabling prop $actions_with_params
 - fixed debugbar notice in db section
 - fixed debugbar css reset
 - added method getScriptsPath() to Peak_Controller_Action

VERSION 0.9.9
-------------
Release Date: 2015-01-04

 - fixed bug in renderArray() of Peak_View_Render. Wrong variable was pushed to render().
 - fixed Peak_View_Helper_Debugbar() to not render the bar when view engine is json
 - added methods enablePersistence() and export2file() to Peak_Config_Json. 
 - fixed _get() preg_match verification in Peak_Zendatable
 - added auto zend db connection config in Peak_Application_Bootstrap
 - added a optionnal params to paging() of Peak_Zendatable
 - simplified a lot Peak_Model_Pagination by using new method _get() from Peak_Zendatable. This remove query building 
   process duplication, give more flexibility, fix previous errors and security issues and standardize the whole thing.
 - added 3 methods to Peak_Zendatable : get(), getAll() and _get(). Those new methods simplifies table models 
   by writing less code for select statements
 - changed if condition is_null to isset in Peak/Model/ActiveRecord.php construtor for $data
 - modified method exists() in Peak_Zendatable to return an array instead whole object when $return_row is true
 - added method loadUrl() for Peak_Config_Json. Support get/post url. Require php extension CURL.
 - added the support of passing an object as well for the model param in Peak_Model_ActiveRecord
 - removed new array syntax in Peak_Model_ActiveRecord to keep backward compatibility with php < 5.3
 - rewrited method __construct() of Peak_Model_ActiveRecord for more flexibility and added final method readOnly()
 - fixed a bug in Peak_Model_ActiveRecord $_readonly was not evaluated in save() and delete()
 - fixed a bug of cleanArray() in Peak_Model_Zendatable that let pass valid column data even if is 
   value is an array(notice array to string converstion). Now if its a valid column and data is an array,
   it will be transformed into json string automatically.
 - added array empty check in new methods _configView() and _configRouter() in Peak_Application_Bootstrap
 - fixed regression introduced in 0.9.8 >
   "fixed group by unquoted identifier of method _getData() in Peak_Model_Pagination"


VERSION 0.9.8
-------------
Release Date: 2014-02-04

 - added a new exception ERR_VIEW_ENGINE_NOT_FOUND to handle error of loading unknown view rendering engine
 - !IMPORTANT: changed the prefix of methods automatically called by Peak_Application_Bootstrap.
   Old prefix : _init  New prefix: init
 - renamed method boot to _boot() in Peak_Application_Bootstrap
 - Peak_Application_Bootstrap now handle app configs injection in Peak_View and Peak_Router.
   Added methods _configView() and _configRouter() for that purpose.
   This also mean that you can add yours own customs regex routes directly in your app config file.
 - removed method _registryConfig() in Peak_View
 - added class Peak_Controller_Action_Json
 - fixed group by unquoted identifier of method _getData() in Peak_Model_Pagination
 - fixed order by unquoted identifier of method _getData() in Peak_Model_Pagination
 - added method quoteIdentifiers() in Peak_Model_Zendatable
 - fixed variable name problem in method translate() of class Peak_Lang
 - fixed a bug in method exists() of class Peak_Model_Zendatable that occur when field key name is also a reserved word.
 - improved method addRegex() of Peak_Router by supporting quick pattern {param_name}:validator in regex. Validator are:
   :any, :negnum, :posnum, :negfloat, :posfloat, :float, :permalink, :alphanum, :alpha, :year, :month, :day. Also, those
   quick validators are searched/replaced by default, but can be turn off with the third param of addRegex()
 - added method getLoadedFiles() to Peak_Lang. Also, when loading file, if lang abbreviation is missing, an exception is thrown.
 - added 3 new methods in class Peak_Helpers : addPath(), setPaths(), getPaths().
 - added method helperObject() to Peak_View. This create/return instance of Peak_View_Helpers.
 - added a fix to Peak_View_Helper_Debugbar to prevent showing the bar on production env
 - added magic method __call() to Peak_Model_Zendatable and get rid of all shortcut methods
 - added a basic but usefull class model Peak_Model_ActiveRecord based on ActiveRecord pattern
 - fixed Peak_Config methods to use array_key_exists() instead of isset()
 - fixed Peak_View_Helpers by preventing in jection of view object if this helper extends Peak_Config class
 - rewrited a good portion of class Peak_Lang. It's now more granular, supports multiple translations files and more 
   independent from others Peak components
 - modified method setColumns() of Peak_View_Helper_Grid for trimming the keys of columns
 - added a param to method getAction() of class Peak_Controller_Action to return action without the prefix
 - updated class Peak_View_Helper_Debugbar to use REQUEST_TIME or REQUEST_TIME_FLOAT of $_SERVER instead of Peak_Chrono
 - changed method baseUrl() from class Peak_View_Render to detect properly https url
 - updated method Peak_Controller_Action::redirectUrl() to use Peak_View_Header class

VERSION 0.9.7
-------------
Release Date: 2013-09-02

 - deleted Peak_View_Helper_Js class given that Peak_View_Helper_Assets do the same thing
 - added method exists() to Peak_View_Helper_Assets
 - deleted deprecated Peak_View_Helper_Html class
 - deleted Peak_View_Helper_Download class given that Peak_View_Header class is implementing this functionnality
 - moved code of Peak_View_Helper_Redirect to new class Peak_View_Header and deleted Peak_View_Helper_Redirect
 - enhanced Peak_Controller_Internal_PkError debug trace output and maded various fixes
 - added in class Peak_View_Header methods codeAsStr(), holdOn(), holdOff(), renamed resetHeader() to reset() and 
   finally added some params to method release() and setRCode()
 - added method header() to Peak_View and modified method render() t<o support this new method
 - added new class Peak_View_Header to facilitate view http headers
 - added method getTriggerTrace() to Peak_Exception. This method retreive element trace who triggered the exception
 - added methods swapKey(), swapKeyVal(), swapKeyValCallback() to Peak_Model_Zendatable
 - added the notion of param(s) after url to Peak_View_Helper_Assets
 - added method getLang() to class Peak_Lang
 - modified option punc of method _filter_alpha() in class Peak_Filters_Advanced to accept also a string as list of punctuations
 - added param $force_insert to method save() of class Peak_Model_Zendatable
 - updated debugbar.js
 - updated Peak_View_Helper_Debugbar to support new css
 - added a less file for Peak_View_Helper_Debugbar, updated icons, added a dark theme and compressed debugbar css
 - added htmlentities encode in console log of Peak_View_Helper_Debugbar
 - removed deprecated method display() in class Peak_View_Helper_Debug
 - added new view helper Peak_View_Helper_Grid
 - added method _filter_datetime() to class Peak_Filters_Advanced
 - updated vendor twitter bootstrap to 2.3.1

VERSION 0.9.6 
-------------
Release Date: 2013-04-09

 - Peak_View_Helper_Debugbar chrono now use $_SERVER['REQUEST_TIME_FLOAT'] if php >= 5.4
 - modified class Peak_Spl_Dirinfo to support recursive and non-recursive directory iterator
 - added param $return_row to method exists() in class Peak_Model_Zendatable
 - fixed missing default value for param $action of method redirect() in Peak_Controller_Action
 - removed php5.4 array syntax in method process() of class Peak_View_Helper_Assets
 - added method _filter_time() to class Peak_Filters_Advanced
 - fixed extended class name typo in Peak_View_Helper_Debugbar
 - added method saveTransaction() to class Peak_Model_Zendatable
 - removed deprecated class Peak_View_Helper_Calendar
 - renamed helpers file with first capital letter
 - fixed table/schema name in _getData() of class Peak_Model_Pagination
 - fixed old bug in class "Peak_Helpers" that prevented methods __get() and exists() from sometimes validate a file's existence 
   on an case sensitive os.
 - added method getLanguages() to Peak_Controller_Helper_Request
 - added an auto-detect file extension to Peak_View_Helper_assets
 - added method renderArray() to Peak_View_Render. This new method we use method render() for each files.
 - added a new exception constant ERR_VIEW_FILE_NOT_FOUND to Peak_View_Exception
 - changed the default path used by method render() in Peak_View_Render_Layouts. This path now point to apppath "theme" instead
   of apppath "theme_layouts". This will imply some changes in existing apps, but it will be more flexible and simpler to 
   include files contents of application related to views.
 - modified Peak_Core::initConfig() to support json config file.
 - added property $_allow_comments to class Peak_Config_Json. This option is set to false by default. If true, json file can
   have comment(s) syntax like /* */ and //. Modified methods __construct() and loadString() to support this new property.
 - fixed bug in login() of Peak_Model_Authentication when hashing and salting the password. We use hashStr() now instead.
 - added method hashStr() to class Peak_Model_Authentication
 - added method _filter_phone() to class Peak_Filters_Advanced
 - added the class alias "Peak" to class "Peak_Core" if PHP >= 5.3
 - removed old functions _clean() and _cleans() from Core.php file
 - removed deprecated property $_extensions and methods __call() and ext() from class Peak_Core
 - fixed a problem with default controller action name. We moved default action name verification to getRoute() and simplified method 
   dispatchAction() in class Peak_Controller_Action
 - modified Peak_View_Helper_Debugbar and debugbar.js to correctly start the bar minimized when debugbar.js need to load jquery cdn
 - renamed class View_Helper_assets to Peak_View_Helper_assets as it should have been.
 - fixed an uncaught exception throw in getFromMethod() of Peak_Annotations when method don't exists
 - fixed params parsing problem with Peak_Annotations::parse() when param contains "-" char
 - replaced method add() by setTags() in class Peak_Annotations. Modified method parse() to accept different var type of $_tags.
 - added class View_Helper_assets for facilitating multiples insertions of files like css/js/img/etc. in views
 - changed method __construct of Peak_Model_Zendatable_Mutator to prevent database change if no table/primary/schema specified
 - modified method save() of class Peak_Model_Zendatable to handle new before/after methods.
 - added 4 new methods to class Peak_Model_Zendatable : beforeInsert(), afterInsert(), beforeUpdate() and afterUpdate().
   Those new methods do nothing by default. Children classes can now implement those ones if needed to alter the way save() work
 - updated vendor twitter bootstrap to 2.3.0


VERSION 0.9.5
-------------
Release Date: 2013-02-12
 
 - fixed Peak_Model_Pagination because of changed visibility of method Peak_Model_Zendatable::query()
 - added method query() in Peak_Model_Zendatable_Mutator with public visibility.
 - passed method query() visibility to protected in class Peak_Model_Zendatable.
 - added class Peak_Model_Zendatable_Mutator. This allow us to perform stuff on the fly on multiples tables.
 - added method getMethodCodeBlock() to class Peak_Zreflection
 - fixed a bug that return empty array with Peak_Filters::globalSanitize() when no filter is set. This method will now
   apply a default string sanitize if no filter is set.
 - added a default unknow error in class Peak_Controller_Internal_PkError
 - added a param to method show() of class Peak_View_Helper_Debugbar that make the bar starting minimized. 
   By default, this new param is set to false.
 - fixed a notice about object to string conversion in method _proccessVariables() of class Peak_View_Render_VirtualLayouts
 - fixed a problem with paths of files in chrono section of class Peak_View_Helper_Debugbar.
 - added property $_is_calculated to Peak_Model_Pagination. With this, we are not forced to call calculate() before 
   retreiving a page.
 - added a default value to $_it_perpage in Peak_Model_Pagination.
 - added method loadFile() to Peak_Lang and modified method __construct() to support this new method. This will allow us
   the use of class Peak_Lang as standalone instead of only in framework context.
 - added method getSchemaName() in Peak_Model_Zendatable and modified methods save(), count() and exists() to use it
 - fixed a variable name error introduced in the last change in cleanArray() of Peak_Model_Zendatable
 - fixed a potential problem with save() in Peak_Model_Zendatable by changing isset for array_key_exists
 - fixed a potential problem with cleanArray() in Peak_Model_Zendatable by changing isset for array_key_exists
 - added protected property $_db to Peak_Model_Zendatable to prevent use of db adapter object outside of the model
 - minor refactoring of method count() in Peak_Model_Zendatable
 - fixed a notice about array to string conversion in method _proccessVariables() of class Peak_View_Render_VirtualLayouts
 - fixed method addAction() in Peak_Codegen_Controller to discard empty action name
 - changed the path of internal module in Peak_Application_Modules
 - added bind param to method query() in Peak_Model_Zendatable
 - added a new param method to model() from Peak_Controller_Action to allow injection of param(s) into the model constructor
 - added better zend_db profiler infos in Peak_View_Helper_Debugbar + minor css change in debugbar.css
 - added few enhancements in debugbar.js and debugbar.css
 - fixed some js functions to use properly window size for panels in debugbar.js
 - removed deprecated file appcreate.php
 - removed vendor Geshi
 - updated css twitter bootstrap in Peak_Controller_Internal_Pkdoc and Peak_Controller_Internal_Pkwelcome
 - updated vendor twitter bootstrap to 2.2.1 and remove version folder to avoid changing integration url each time
 - fixed a bug in Peak_Controller_Internal_Pkdoc introduced by changes of Peak_View_Render_VirtualLayouts in previous version

VERSION 0.9.4
-------------
Release Date: 2012-11-06

 - added method cleanUnknownVars() and property $_clean_all_unknown_vars in Peak_View_Render_VirtualLayouts
 - modified method initConfig in Peak_Core to support absent or unsupported application config by using genericapp.ini 
   only in development env
 - enhanced Peak_Controller_Internal_Pkwelcome functionality and content
 - added a quick variables template system for Peak_View_Render_VirtualLayouts by adding method _processVariables()
   and by modifying method render()
 - remodified the recent change about an exception throw in Peak_Core::initConfig(), by enable it again only if 
   app environment is not development
 - marked as deprecated property $_extensions, methods __call() and ext() from class Peak_Core
 - added class Peak_Controller_Internal_Pkwelcome. This new controller is used by default in genericapp.ini
 - added file genericapp.ini in Peak/Application
 - disabled an exception throw in Peak_Core::initConfig() when loading an empty app config file
 - fixed notice in method initConfig() of class Peak_Core due to the change in Peak_Config_Ini
 - fixed an exception throw in Peak_Config_Ini in case of empty file
 - added property $_password_salt and method getSalt() to class Peak_Model_Authentication
 - marked class Peak_View_Helper_html as deprecated
 - updated Peak_Controller_Internal_Pkdoc with new version of twitter bootstrap
 - updated vendor twitter bootstrap to 2.1.0
 - fixed method _filter_match_key() of class Peak_Filters_Advanced to also return false if the key doesn't exists
 - modified method initConfig() of class Peak_Core because of new method loadFile() in Peak_Config
 - added method loadFile() to Peak_Config and modified method __construct() to support this new option too
 - added method log() to debugbar helper.
 - added css reset to debugbar.css
 - fixed view helper debugbar to not show when rendering is disable
 - deleted previously introducted noRender() in Peak_View and added instead methods canRender(), disableRender() and 
   enableRender() and modified method render()
 - fixed debugbar.js to not load jquery when it's already loaded
 - modified function _autoloadAppBaseCustom() to support parent models in case of customs app models folder in autoload.php
 - fixed method count() to support where string correctly in Peak_Model_Zendatable
 - fixed methods calculate() and _getData() to handle correctly pages when no result in class Peak_Model_Pagination
 - added some punctuations to _filter_text() in class Peak_Filters_Advanced
 - added method _filter_date() to class Peak_Filters_Advanced
 - added method _filter_url() to class Peak_Filters_Advanced

VERSION 0.9.3
-------------
Release Date: 2012-06-30

 - method dispatchActionParams() is no more experimental in Peak_Controller_Action
 - changed property $actions_with_params to true by default in Peak_Controller_Action
 - added method noRender() in class Peak_View for disabling view rendering and modified method render() to support this.
 - fixed the call of Peak_Registry::obj() to Peak_Registry::o() in Peak_Lang
 - fixed a method name error in Peak_Model_Zendatable
 - fixed unwanted double slashes of server request uri in Peak_Router::getRequestURI()
 - updated Peak_Controller_Internal_Pkdoc with new version of twitter bootstrap
 - updated vendor twitter bootstrap 
 - added a new exception constant in Peak_Controller_Exception 
 - added method dispatchActionParams() and property $actions_with_params in Peak_Controller_Action. This allow us, when activated,
   to call actions with arguments directly and force action url to have those arguments instead of checking them with property
   $params and method params() inside action method. Note that this mode is more strict and force url to contains those params for
   a specific action.
 - fixed an illegal string offset bug in Peak_Core::initConfig() for PHP 5.4
 - added methods addValidateFilter() and addValidateFilters() in Peak_Filters_Advanced
 - added method calculate() in Peak_Model_Pagination
 - added method paging() in Peak_Model_Zendatable to access to Peak_Model_Pagination 
 - added new class Peak_Model_Pagination for Peak_Model_Zendatable
 - added 4 new shorcut method for escaping stuff in Peak_Model_Zendatable

VERSION 0.9.2
-------------
Release Date: 2012-02-24
 
 - added class Peak_Model_Authentication to simplify authentication mechanism with database 
 - fixed method preAction() of Peak_Controller_Internal_PkError due to recent change in Peak_View_Cache
 - modified method __construct() from Peak_Application_Modules to use newly created method in Peak_Application 
 - added 2 methods in Peak_Application to clean up method __construct()
 - removed property $_ctrl_name in Peak_Application_Modules and refactored method prepare()
 - fixed two small bugs in Peak_View_Helper_Debugbar
 - fixed method cleanArray() in Peak_Model_Zendatable to force Zend_db to describe table. This prevents
   issues in some case where method save() is the first interaction with the table and $_metadata are not 
   yet gathered.
 - added new Peak_Exception error constant called. This exception can be raise by Peak_Core::initConfig()
 - added Zend_Db_Profiler infos if enabled to Peak_View_Helper_debugbar
 - modified class Peak_View_Helper_debugbar and moved js and css to debugbar folder
 - updated vendor zend to version 1.11.11
 - added twitter bootstrap to vendors 
 - fixed html entities problem in Peak_Controller_Internal_Pkdoc
 - fixed another class name problem on certain case sensitive os with Peak_View_Helper_form, Peak_View_Helper_form_select, 
   Peak_View_Helper_form_input and Peak_View_Helper_tagattrs

VERSION 0.9.1
-------------
Release Date: 2011-11-28

 - added class Peak_Annotations
 - fixed class name case sensitive problem on certain os with Peak_View_Helper_form, Peak_View_Helper_form_select and
   Peak_View_Helper_form_input
 - fixed some display bugs in Peak_Controller_Internal_Pkdoc
 - fixed method save() from Peak_Model_Zendatable to return primary key on update query
 - fixed a bug introduced in Peak_Controller_Front when added $router property
 - fixed method isValid() from Peak_View_Cache to support custom cache id
 - removed all shorcut methods about cache in Peak_View_Render
 - renamed methods isCached() to isValid(), enableCacheStrip() to enableStrip(), isCachedBlock() to isValidBlock(),
   cacheBlockEnd() to blockEnd() in Peak_View_Cache
 - fixed bug in isCached() of class Peak_View_Cache
 - deleted deprecated class Peak_View_Helper_Country 
 - added method getColunms() in Peak_Model_Zendatable
 - removed properties $_auto_describe and $_describe and method describe() in class Peak_Model_Zendatable.
   All those informations are already in the parent class, modified methods __construct() and cleanArray() accordingly
 - modified method redirectAction() to use method redirect() in Peak_Controller_Action
 - added $router property in Peak_Controller_Front, Router object is now also stored in the Front Controller, 
   modified Peak_Application accordingly
 - modified methods getSize() from class Peak_Spl_Dirinfo and Peak_Spl_Fileinfo
 - minor change to css in Peak_View_Helper_Debugbar
 - added method postDispatchController() in Peak_Controller_Front

VERSION 0.9.0
-------------
Release Date: 2011-10-02

 - fixed problem with method getProperties() with value when class is abstract or is an interface in class Peak_Zreflection
 - rewrited method getConstants() from Peak_Zreflection
 - modified method getProperties() to retreive also values of properties in class Peak_Zreflection
 - fixed Peak_View_Helper_Debugbar by encoding html entities in html/js code data 
 - added new internal controller class named Peak_Controller_Internal_Pkdoc
 - added new class named Peak_Zreflection_Class
 - fixed some class name of Peak_View_Helper_*
 - removed static verification from getPropertyVisibility() and getMethodVisibility() and static status to
   getMethods() and getProperties() instead in class Peak_Zreflection
 - renamed view helpers classes debugbar and js based on framework class naming convention
 - changed vars print order in class View_Helper_Debugbar
 - fixed method getPropertiesByInheritance() following the change of getProperties() in Peak_Zreflection
 - rewrited completly getProperties() to return all infos of properties in array format in Peak_Zreflection
 - modified method docTagsToArray() to accept object or array of object in Peak_Zreflection
 - fixed method getMethodsByInheritance() following the change of getMethods() in Peak_Zreflection
 - rewrited completly getMethods() to return all infos of methods in array format in Peak_Zreflection
 - added method getMethodDeclaration() in Peak_Zreflection
 - modified array key name inside method docTagsToArray() of class Peak_Zreflection
 - modified method getMethodDocTags() from Peak_Zreflection to use docTagsToArray() instead of 
   returning Zend_Reflection_Docblock object
 - fixed variable name error in method __call() of class Peak_Controller_Action
 - fixed array offset problem in _exception2table() of class Peak_Controller_Internal_PkError
 - fixed method getMethodDoc() who was not returning long description in class Peak_Zreflection
 - fixed variable name error in getMethodsByInheritance() of class Peak_Zreflection
 - fixed minor trim problem of method getClassDeclaration() in Peak_Zreflection
 - modified getClassDocTags() of Peak_Zreflection to return array instead of object
 - added methods getParentMethods(), getSelfMethods(), getParentProperties() and getSelfProperties() to Peak_Zreflection
 - fixed method paramsToArray() from Peak_Zreflection
 - added methods getMethods(), getProperties() and getConstants() to Peak_Zreflection
 - added method query() to Peak_Model_Zendatable
 - fixed methods render() and output() in Peak_Render_VirtualLayouts due to recent change in Peak_View_Render
 - fixed Vendors path name in autoload.php
 - added constant PHP_CLOSE_TAG to Peak_Codegen
 - passed method generate() to abstract method in Peak_Codegen
 - moved method getIndent() from Peak_Codegen_Class to Peak_Codegen
 - fixed method generate() that was not using $_is_abstract property in Peak_Codegen_Class
 - added property $_is_final and method isFinal() to Peak_Codegen_Class
 - added null default value to param $path of method Peak_View_Render::render()
 - modified method init() from Peak_Core to throw an exception when path(s) or config constants are missing 
 - added new constant to Peak_Exception and updated method handleErrConstToText()
 - updated Zend Framework version 1.11.1 to 1.11.10 in vendors folder
 - updated geshi version and deleted old php folder in vendors folder
 - renamed libs folder to vendors, changed set_include_path in Peak_Core accordingly
 - fixed method Peak_Config_Json::_jsonError(), JSON_ERROR_UTF8 constant don't exists until php 5.3.3
 - added abstract method render() and output() to Peak_View_Render
 - deleted deprecated file boot.php. Use Peak_Core::init() instead

VERSION 0.8.7
-------------
Release Date: 2011-08-22

 - udpated method matchRegex() from Peak_Router to accept regex named subpatterns as controller params
 - modified token separator from '.' to more natural '/' char in Peak_Router::addRegex()
 - added method redirectUrl() in Peak_Controller_Action
 - added method renderwithjQueryTag() to class View_Helper_js
 - added class Peak_Controller_Helper_Request
 - fixed method redirect() of Peak_Controller_Front to support an non-array $params
 - fixed method getTitle() in Peak_Controller_Action for Peak internal controller classes
 - deprecated boot.php
 - patched regression problem with recent changes of Peak_View::__get() and Peak_Config::__get()
 - modified method Peak_View::__call() to allow calling helper class like a method with arguments. 
   first argument represent method name and the others , the method arguments.
 - added passing by reference to __get() method in Peak_View
 - modified method Peak_Controller_Internal_PkError::_exception2table()
 - optimized Peak_Core::initConfig() by removing copy of configs array  
 - added passing by reference to __get() method in Peak_Config
 - simplified Peak_Exception, added class Peak_Controller_Exception and Peak_View_Exception and modified many classes accordingly
 - modified method Peak_Controller_Front::errorDispath() to return Peak_Application instance
 - fixed method Peak_Application::_construct() due to missing keywork "new" before Peak_Controller_Front object
 - added View helper js

VERSION 0.8.6
-------------
Release Date: 2011-05-29

 - added params to method helper() in Peak_View
 - fixed problem with $params in method Peak_Controller_Front::redirect() and modified method Peak_Router::setRequest() accordingly
 - corrected method name _filter_length() in Peak_Filters_Advanced
 - added method model() in Peak_Controller_Action
 - added method _globalSanitizeRecursive() in Peak_Filters and modified globalSanitize() to support multidimensionnal array
 - added method _filterRecursive() in Peak_Controller_Helper_Post to support multidimensionnal post sanitization
 - added class Peak_Model_Zendatable
 - fixed _filter_float() constant name error in Peak_Filters_Advanced
 - added filter text to Peak_Filters_Advanced
 - added method Peak_Core::init() to emulate boot.php process step by step
 - minor fix to class name in file Peak/Filters/Data.php
 - added method redirectAction() in Peak_Controller_Action
 - added method _filter_float and fixed _filter_alpha to support french chars in Peak_Filters_Advanced
 - moved front controller postDispath() call to Peak_Application::run()
 - resolved paths in boot.php with realpath()
 - fixed Peak_Codegen_Bootstrap because we have renamed bootstrap class
 - moved code from Peak_Core_Extension_Modules to Peak_Application_Modules and deleted modules core extensions
 - added method getEngineName() to Peak_View
 - moved class Peak_Bootstrap to /Application/ folder and renamed it Peak_Application_Bootstrap
 - added methods setVars() and addVars() to Peak_View
 - fixed minor problem of css style in Peak_View_Helper_Debugbar
 - fixed method validate() for array key errors that do not exists in Peak_Filters_Advanced
 - updated method output of Peak_View_Helper_Form_Select and Peak_View_Helper_Form_Input

VERSION 0.8.5
-------------
Release Date: 2011-03-12

 - added Peak_View_Helper_TagAttrs, Peak_View_Helper_Form, Peak_View_Helper_Form_Select, Peak_View_Helper_Form_Input
 - added special filters in Peak_Filters_Advanced and fixed method validate()
 - fixed minor bug in Peak_View_Render_Layouts::render()
 - fixed minor bug in method getFiles() from Peak_View_Helper_Debug
 - added punctuation option in method Peak_Filters_Advanced::_filter_alpha()
 - added method flushVars() to Peak_Config
 - added class Peak_Controller_Helper_Post
 - fixed a bug in method Peak_Filters_Advanced::_filter_alpha()
 - added class Peak_Model_DataObject
 - deleted deprecated class Peak_View_Helper_Icon
 - rewrited method iniVar() from Peak_View to use Peak_Config_Ini.
 - deleted property $actions and renamed method listActions() to getActions() in Peak_Controller_Action.
 - deleted properties $name and $title in favor of new methods getName() and getTitle() in Peak_Controller_Action and 
   modified initController() method accordingly.
 - modified exception constants messages in Peak_Exception.
 - removed method setRenderEngine() and moved code in engine() method. Use engine() for setting and getting render engine instead.
 - changed property $params_assoc from array to config object and added method params() to make easy access to params key name.
   instead of accessing to params with $this->params_assoc['var'], we use now something like this: $this->params()->var.
 - added method __construct() to Peak_Config.
 - fixed a bug in method _exception2table() of class Peak_Controller_Internal_Pkerror.
 - fixed method _filter_alpha() of Peak_Filters_Advanced to support spaces.
 - added property $_global_sanitize and method globalSanitize() in Peak_Filters.
 - added method getRoute() in Peak_Controller_Action.
 - replaced handleAction() method of Peak_Controller_Action for dispatch() and dispatchAction().
 - fixed problem with method redirect() in Peak_Controller_Front when redirecting to an action in the same controller.
 - added class Peak_Controller_Internal_PkError
 - minor fixes to Peak_View_Render_VirtualLayouts
 - splited dispatch() method of Peak_Controller_Front into dispatch(), _dispatchController(), _dispatchControllerAction() and
   _dispatchModule(). Added posibility to inject exception object to error controller with errorDispatch().
 - added class Peak_View_Render_VirtualLayouts
 - fixed Peak_Application_Modules to flush previous bootstrap if modules don't have his own
 - fixed Peak_Application_Modules to load default Peak front controller if modules don't have his own
 - added class Peak_Filters_Data

VERSION 0.8.4
-------------
Release Date: 2011-02-07
 
 - simplified class Peak_Controller_Helper_Validate
 - created Peak_Controller_Helper class and moved there method __call() from Peak_Controller_Helpers
 - added function _autoloadAppBaseCustom() in autoload.php
 - fixed a bug inside method render() in Peak_View_Render_Layout when layout file is specified but do not exists
 - added filter enum to Peak_Filters_Advanced
 - fixed the problem with custom app class autoloading inside autoload.php. To avoid confusion and problems with case-sensitive
   file system, app files and folders name should be in lower case except for controllers files that must have a 
   upper 'C'(ex: indexController.php, contactController.php, etc...)
 - fixed the problem with $_SERVER['DOCUMENT_ROOT'] not ending by / in some case inside boot.php
 - fixed method _filter_int() in Peak_Filters_Advanced to support integer ranges(min,max)
 - fixed double slash of paths in boot.php
 - deleted deprecated method config() in Peak_Core
 - added static methods reset(), resetAll(), getMS(), isCompleted() and _elapsed() to Peak_Chrono
 - rewrited class Peak_Chrono. This classs now manage a global timer and/or multiple timers
 - fixed method save() from Peak_Codegen and added constant PHP_OPEN_TAG
 - added class Peak_Codegen_Front and Peak_Codegen_Index
 - fixed method validate() of Peak_Filter_Advanced to manage $_data keyname not set
 - fixed method validate() of Peak_Filters_Advanced to throw exception when filter method is not found
 - fixed bug with Peak_View::setRenderEngine() and Partials render engine
 - deleted deprecated class Peak_Pattern
 - splited class Peak_FormValidation into Peak_Filters_Advanced and Peak_Filters_Form
 - simplified Peak_Filters and moved some code into Peak_Filters_Basic
 - deleted methods _filter_word() and filter_words() in favor of new method _filter_alpha() and _filter_alpha_num(), and added
   method getFiltersList() in Peak_FormValidation
 - added abstract class Peak_FormValidation
 - added methods _setError() and _regexp() to Peak_Filters
 - added methods getSanitizeFilters() and getValidateFilters() to Peak_Filters
 - added abstract class Peak_Filters
 - renamed constants _VERSION_, _NAME_ and _DESCR_ to PK_VERSION, PK_NAME and PK_DESCR

VERSION 0.8.3
-------------
Release Date: 2010-12-17

 - added class Peak_Controller_Helper_Validate
 - modified Peak_Controller_Helpers to support peak library controller helpers too
 - added method exists() in Peak_View_Theme
 - deleted deprecated methods setOptions() and getOptions() in Peak_View_Theme 
 - fixed and renamed method folder() to setFolder() in Peak_View_Theme
 - added methods __construct() and _jsonError()(PHP 5.3) to Peak_Config_Json
 - added method postRender() in Peak_Controller_Front and fixed render() in Peak_Application
 - fixed method dispatch() of Peak_Controller_Front when dispatching the default controller 
 - added property $error_controller and method errorDispatch() in Peak_Controller_Front
 - deleted deprecated classes Peak_Core_Extension_Codegen, Peak_Core_Extension_Configs and Peak_Core_Extension_Lang
 - rewrited Peak_Codegen_Controller class to use Peak_Codegen_Class
 - rewrited Peak_Codegen_Bootstrap class to use Peak_Codegen_Class
 - fixed minor problem with methods generate() in Peak_codegen_Class_Method, Peak_codegen_Class_Property and
   Peak_codegen_Class_Param
 - added constants INDENTATION_SPACE and LINE_BREAK to Peak_Codegen
 - added classes Peak_Codegen_Class, Peak_codegen_Class_Constant, Peak_codegen_Class_Docblock, Peak_codegen_Class_Element, 
   Peak_codegen_Class_Method, Peak_codegen_Class_Param, Peak_codegen_Class_Property
 - fixed Peak_Application so that it is no longer a singleton and removing $_instance and getInstance()
 - renamed property $allow_internal_controller to plural in Peak_Controller_Front and fixed method _registryConfig() to force
   boolean conversion for this property. 
 - deleted deprecated class Peak_Utils
 - fixed methods __construct() and __call() of Peak_Core to use getEnv() instead of deprecated constant DEV_MODE
 - fixed bug in Peak_Helpers::__get() by changing include for include_once
 - moved method _arrayMergeRecursive() from Peak_COnfig_Ini to Peak_Config, rename to arrayMergeRecursive() and changed
   visibility to public. fixed Peak_Core::initConfig() accordingly.
 - fixed Peak_View::iniVar() to not use '#' in ini file (# deprecated in 5.3) and to support loading a 
   ini file from a custom path. Variables used inside key value use syntax like $[myvar]. 
 - added method _registryConfig() in Peak_Controller_Front. Config use keyname 'front'. Modified method Peak_Application::run(),
   Peak_Controller_Front::dispatch() and Peak_Application_Modules::run() accordingly.
 - added property $allow_internal_controller to Peak_Controller_Front and deprecated 
   the use of constant ENABLE_PEAK_CONTROLLERS
 - added method _registryConfig() in Peak_View. We can now call methods for Peak_View inside an application 
   configuration file like set(), setRenderEngine(), rendering engine methods, etc... Config use keyname 'view'
   ex: view.set.myvar = "data", view.setRenderEngine = "Layouts", etc...
 - fixed Peak_Config_Ini::loadFile() to throw a exception if ini file contains syntax error(s) (php 5.2.7+)
 - rewrited appcreate.php to support new important changes
 - fixed listActions to flush $actions before listing actions methods and added support of $action_prefix to regex.
 - moved string to array conversion from matchRegex to addRegex in Peak_Router 
 - deleted deprecated folder views/functions

VERSION 0.8.2
-------------
Release Date: 2010-12-10

 - fixed getRequestURI() when $base_uri = '/' (it mean that app located at the base of server url ex: http://example.com)
 - fixed double slash in url of method Peak_Router::setBaseUri() and Peak_Render::baseUrl()
 - method getObjectList() renamed to getObjectsList() in Peak_Registry
 - cleaning tests folder and simpletest and switching to PHPUnit
 - added method getRoute() in Peak_Controller_Front and moved code from preDispatch() there. 
 - fixed minor error in Peak_Application::__construct(). bootstrap instance was not saved into Peak_Application $bootstrap
   property. removed registration of Peak_Core in Peak_Registry since its a singleton.
 - removed getFunctionsFile() from Peak_View. App views file functions.php feature removed in favor of view helpers objects.
 - method run() of Peak_Application returns the object itself.
 - methods isController(), isInternalController() and isModule() moved from Peak_Core to Peak_Controller_Front.
   fixed Peak_Controller_Front accordingly.
 - deprecated constant APP_THEME and modified Peak_Core::getDefaultAppPaths() accordingly
 - removed experimental stuff about plugins in Peak_Core
 - deleted deprecated method init() in Peak_Core
 - added support of php ini_set inside application configuration file (Peak_Core::initConfig)
 - updated methods Peak_Core::getPath() and Peak_Core_Extension_Modules::init() to use Peak_Core::getDefaultAppPaths().
 - method initApp() modified and renamed getDefaultAppPaths() in Peak_Core. The utility of this method is now to generate 
   array of default paths of a application.
 - Peak_Core::initConfig() now called inside initApp() and modified boot.php accordingly. This change bring the possibility
   to a modify the folders structure of an application inside its own configuration files.
 - added method getClassName(), removed obj() and renamed $_registered_objects to $_objects in Peak_Registry
 - modified boot.php to include autoload.php before launching Peak_Core
 - added method initConfig() in Peak_Core and deprecated method init()
 - added method setVars() to Peak_Config
 - added static method getEnv() and static private property $_env in Peak_Core, fixed Peak_Bootstrap accordingly
 - deprecated constants PROJECT_NAME, PROJECT_DESCR, APP_DEFAULT_CTRL, DEV_MODE
 - renamed constant ROOT to PUBLIC_ROOT, ,ROOT_ABSPATH become PUBLIC_ABSPATH and ROOT_URL become PUBLIC_URL.
   Fixed method baseUrl() in Peak_View_Render accordingly
 - minor fix of method baseUrl() in Peak_View_Render
 - minor fix of method _load in Peak_Config_Ini

VERSION 0.8.1
-------------
Release Date: 2010-11-17
 
 - modified Peak_View_Helper_Debug and added Peak_View_Helper_Debugbar.  
 - modified Peak_Helpers to support array of class name prefixes, library view helpers classes prefix renamed to 
   Peak_View_Helper_. Peak Helper can be extended by application now.
 - added method set() to Peak_View.
 - deprecated views/functions/ folder and files, moved general.php functions to new view helper html.php
 - added method getExtension() to Peak_Spl_FileInfo
 - added class Peak_Spl_Dirinfo
 - deprecated Peak_Core_Extension_Codegen in favor of new Peak_Codegen abstract class 
 - added another syntax support for regex route in Peak_Router.
   Standard syntax : addRegex([my regex], array('controller' => 'cltrname', 'action' => 'actionname'));
   New other way: addRegex([my regex], 'cltrname.actionname');
 - Controllers actions prefix name are now managed by Peak_Controller_Action only. It make thing cleaner outside of controllers.
   This change has been fixed in Peak_Controller_Front, Peak_Router.
   We can also customize action prefix name in controllers even if it's not recommended for consistency reasons.
 - added function _autoloadClass2File() to autoload.php
 - fix methods isModule() and isController() from Peak_Core and removed the deprecated method getControllers()
 - application custom classes name prefix changed in autoload.php. 
   Old class name prefix : Application_..., New class name prefix : App_... 
 - fix a potential security threat in Peak_Exception constants messages.
 - fix a problem of uniformisation of $_SERVER['REQUEST_URI'] and $request_uri variables in Peak_Router::getRequestURI(). 
 - fix a problem with regex in Peak_Application_Modules. With this fix, modules can specifing theirs own regex.
 - added method deleteRegex() in Peak_Router.
 - renamed $_module_name and $_module_path, added methods getPath() and isInternal() in Peak_Application_Modules.
 - Peak_Application_Modules instance is now stored in Peak_Application::$module.
 - added method getName() to Peak_Application_Modules.
 - added classes Peak_Config_Ini and Peak_Config_Json.
 - visibility of $_vars from Peak_Config has been changed to protected to have the ability of extending the class.

VERSION 0.8.0
-------------
Release Date: 2010-11-05

 - added constant ERR_CUSTOM to Peak_Exception
 - autoload.php > remove deprecated function _autoloadAppMod()
 - Peak_Zreflection > added methods getMethodsByInheritance() and getPropertiesByInheritance()
 - fixed Peak_View_Theme to throw new exception ERR_VIEW_THEME_NOT_FOUND when loading a theme folder that doesn't exists.
 - fixed rendering engines to throw exception with only filename instead of absolute path.
 - Peak_Exception > renamed ERR_VIEW_TPL_NOT_FOUND to ERR_VIEW_SCRIPT_NOT_FOUND and updated rendering engines.             
 - Peak_Exception > deprecated method getDebugtrace()
 - Peak_Exception > removed property $_trace. never used and we can achieved the same effect with parent class methods
 - Peak_Core > added set_exception_handler('pkexception') to handle all uncaught exceptions (try/catch block missing)
 - fixed method output() visibility in Peak_View_Render_Json
 - fixed minor bug with views cache in Peak_View_Render::preOutput() and Peak_View_Cache::isCached()
 - Peak_Exception > added constant ERR_CTRL_ACTION_NOT_FOUND. Fixed Peak_Controller_Action:handleAction().
                    This fix url problem with valid controller and unknow action.
 - Peak_Controller_Front > Added method forceDispatch() and remove param $flush_request of method dispatch()
 - Peak_Router > added property $_regex, methods public addRegex() and protected matchRegex(). 
                 Tweaked getRequestUri() to support matchRegex().
                 We can now also specify custom url with regular expression.
 - Peak_View_Render > properties $_script_file and $_Script_path renamed and visibility passed to public
 - added class Peak_View_Cache > all cache logic inside Peak_View_Render have been moved there. There is no change
                                 in how we use view cache but it lightweight Peak_View_Render 
                                 when we do not use cache functionnality
 - Peak_View_Render > added methods deleteCache()

VERSION 0.7.99
--------------
Release Date: 2010-08-16

 - View_Helper_Debug > added methods getControllerSource() and getScriptSource()
 - Peak_Controller_Action > remove deprecated method view()
 - Peak_Zreflection > added, fixed and tweaked some methods. 
 - Peak_Config > added method getVars()
 - Peak_View_Theme > moved method getFunctionsFile() to Peak_View. Fixed Peak_View_Render_Partials 
                     and Peak_View_Render_Layouts accordly.
 - Peak_View > rename properties $vars to $_vars, $theme to $_theme and $view_engine to $_engine. 
               'Layouts' is now the default rendering engine
 - Peak_Xml > fix issue with curl_get_content() method
 - Peak_Controller_Action > added method redirect() that point to Peak_Controller_Front method. 
 - Peak_Controller_Front > added method redirect() we can now redirect to an other controller and/or action 
                           inside application controllers.
 - Peak_Router > added method setRequest(), usefull for setting manually a routing request array
 - Peak_Controller_Action > deprecated method view()
 - Peak_Application > added method render(). this method point to front->controller->render(). This simplify the application
                      index so we can do $app->render(); after $app->run(); instead of $app->front->controller->render();

 - Peak_Controller_Front > added method postDispatch()
 - Peak_Controller become Peak_Controller_Action
 - Peak_Controller > removed zendAction() and isZendAction(). fixed handleAction() and 
                     listActions() accordly to that change
 - Peak_Application > property $controller replaced by $front that point to Peak_Controller_Front.
                      This object don't access anymore to Peak_Router object
                      Removed deprecated method validSession().
                      Now, Peak_Application do what it suppose to do: start mvc, bootstrap and delegate 
                      controller dispatching process to Peak_Controller_Front.
 - added Peak_Controller_Front > moved dispatching logic of Peak_Application in this new object

VERSION 0.7.98
--------------
Release Date: 2010-08-07

 - Peak_View_Render > added magic method that point to Peak_View __isset()
 - Peak_View > added magic method __isset()
 - Peak_Controller > added method view()
 - Peak_Core_Extension_Modules > remove method deprecated get(). use getList() instead.
 - fixed Peak_Core > if constant APP_THEME exists, it will set application theme folder, otherwise it will use /views/ 
 - Peak_View_Theme > application /views folder is used as theme folder by default. 
                     method folder() accept null as argument for (re)setting this default behavior. 
                     Otherwise method folder() will change views theme folder to /views/themes/[themename]/
 - Peak_Core > removed deprecated $_controllers, cacheControllers(), getCachedControllers()
 - Peak_Application > moved function _clean() and _cleans() to Peak_Core
 - added class Peak_Spl_FileInfo
 - added new folder for extending spl classes.
 - Peak_Core > added '_path' suffix to core path configurations. fixed getPath() to add this suffix and by way ensured
               that no change is needed outside and separate path configs from other core configs.
 - Peak_Core > added static method config(). get/set core configs            
 - Peak_View_Theme > added method folder(). It set the theme folder name to use. 
                     Tweaked Peak_View::theme() to use this new functionnality
 - Peak_Registry > deprecated method obj() in favor of method shorcut o()
 - Peak_Application_Modules > simplified method run(). It now call Peak_Application run method instead of 
                              trying to emulate his job
 - Peak_Router > added method reset(). Ensure that the router vars are clean before using getResquestUri()
 - Peak_Core > lighten the file by moving/deling methods getModules(), getModule(), getCachedModules()
               and property $_modules

VERSION 0.7.97
--------------
Release Date: 2010-08-04

 - Peak_Core > method initModule() moved to a new extension file(Peak_Core_Extension_Modules) and renamed init()
 - added Peak_Core_Extensions derived from Peak_Helpers, ext() method simplified in Peak_Core 
   and moved extensions in Peak/Core/Extension
 - Peak_Core > Peak_Configs is stored in Peak_Registry under the name of 'core_config'
 - added Peak_Application_Modules > allow to create/use complet modules application inside application                             
 - Peak_Core > added methods init(), initApp() and initModules(). Removed methods setPath() and getPaths().
               modified getPath() to point to new Peak_Config object.
               A lot of files was affected by this change because we replace a lot of php constants by
               Peak_Core::getPath() static method (boot, autoload, Controller, Controller Helpers, 
               View, View Helpers, View Render Partials/Layouts and View theme)
 - added Peak_Config > this new class allow core paths configuration to be overdriven and get rid of all paths constants
 - Peak_Router > minor fix to method getRequestURI()
 - Peak_View_Helper deleted, no more usefull since Peak_View_Helpers accomplish the same.
   Important change: View Helpers don't have to extends anymore this class.
 - Peak_Controller_Helpers have access to others helpers and current controller method
 - Peak_Controller > no more need to init a controller helper before using it.
 - Peak_Helpers > added method exists()
 - added Zend/Loader.php since Zend/Reflection/Docblock classes require this file.
 - View helpers new class name prefix: 'View_Helper_'
   Controller helpers new class name prefix: 'Controller_Helper_'
   Those changes standardize class name for application and library helpers.
 - deprecated Peak_Utils class 
 - Peak_Controller_Helpers now support native peak library controller helper folder too.
 - Peak_Exception > added constant ERR_HELPER_NOT_FOUND
 - added Peak_Helpers abstract class > Peak_View_Helpers and Peak_Controller_Helpers now extends this class of instead
                                       duplicating the code for almost the same behavior. Extends this class
                                       to create your own helpers objects container with multiple paths.

VERSION 0.7.96
--------------
Release Date: 2010-07-29

 - Peak_Core > added method isInternalController(), updated run() method from Peak_Application to use this. 
 - Peak_Application > fix method run() to set router variables instead of using getRequestUri() 
                      when using $flush_request. 
 - Peak_Router > fix method getRequestUri() > app default controller was called when using fake rewrited url
                 ending by .php extension witch is not good. Now it throw exception ERR_ROUTER_URI_NOT_FOUND
 - Peak_Exception > added constant ERR_ROUTER_URI_NOT_FOUND
 - added Peak_View_Helper_Redirect > manage url and controller http header + status code redirection
 - deprecated constants APP_LANG, and Peak_Lang class is no more loaded each time in Peak_Application
 - Peak_Router > fixed method setBaseUri() to use constant SVR_URL as base when $base_uri is empty
 - Peak_Boot > fixed an issue with include path of Peak_Core
 - Peak_Application > add exception when using internal login but application can't find the login controller
 - Peak_View_Render > fixed baseUrl(). remove unwanted double slash // in url
 - fixed Peak_View_Helper_Download problem with file name.

VERSION 0.7.95
--------------
Release Date: 2010-07-19

 - added Zend_Mail and Zend_Mime, updated files to zend version 1.10.6
 - Peak_View_Render > fixed baseUrl(). Sanitize url and add boolean option $return
 - Peak_View > __call() can now load a new helper on the fly. 
               So you are no more obligated to call helper()->object_name before using object_name() method shorcut
 - Peak_View_Helpers > added method exists(). Check if helper file name is found or not.
 - Peak_Controller > added magic method __call(). We can now call a controller helper name like method name.
                     Note: this method won't load a helper and can't be used if helper 
                     name wasn't loaded before with method helper(). This restriction affect Peak_View::_call() too.
 - Peak_Controller_Helpers > added magic method __isset().
 - Peak_View_Helpers > added magic method __isset(). 
 - Peak_View > __call() will now in order check if method is a View_Engine method or Helper object name.
               if nothing is find, it simply silence error or, on DEV_MODE, will trigger E_NOTICE.
               We can now call a view helper name like method name too:
               Normal way -> $this->helper()->text->truncate('...')
               New way -> $this->text()->truncate('...')
 - Peak_View_Render > remove some code in __call(). Now __call() will use Peak_View object for 
                      calling unknow Peak_Render methods like helpers and theme.
 - autoload.php > removed method catch_autoload_err() and _autoload_err().
                  use method Peak_Exception::getDebugTrace() instead
 - Peak_Exception > added method getDebugTrace(), added constants ERR_VIEW_HELPER_NOT_FOUND and ERR_CTRL_HELPER_NOT_FOUND.
                    updated Peak_View_Helpers and Peak_Controller_Helpers accordly to this.

VERSION 0.7.9
-------------
Release Date: 2010-07-09

 - Peak_Application > change to sha1 all md5 in validSession()
 - added class Peak_Controller_Helpers > fixed Peak_Controller helper(). Same changes as Peak_View helper()
 - added class Peak_View_Helpers > fixed Peak_View helper() to use this new helpers objects wrapper.
                                    We use now $this->helper()->myclass->mymethod() instead of
                                    $this->helper('myclass')->mymethod()
 - Peak_View > fixed a second issue with setRenderEngine() when calling unknow rendering engine.
 - Peak_Router > added property $params_assoc. fixed resolveRequest() to added params as associative array.
                 added property $params_assoc to Peak_controller too.
 - Peak_Render > fixed __call() for unknow method to check in Peak_View object if method exits and call it.
                 Removed method helper().
 - fixed an issue of render() in Peak_Render_Layouts and Peak_Render_Partials 
   when use cache and render some file inside views.
 - Peak_Render_Layouts > added method isLayout(). fix render() to allow inclusion of layout scripts 
                         inside view without specifying the whole path. Tweaked useLayout() to use isLayout().
 - Peak_Controller > remove property $c_prefix. renamed property $c_actions to $actions.
 - Peak_Render > modified method baseUrl(). Deprecated $view object variable in views file. Use $this instead.
 - Peak_Render > added method enableCacheStrip() and property $_cache_strip. By default $_cache_strip is false. 
 - Peak_Router > added method setBaseUri() and moved code from __construct() there.
 - Peak_Controller > remove _listActions() from __construct(). Can be called on demand inside controllers child
                     but no more needed each time by handleAction()
 - Peak_Controller > added methods isAction(), isZendAction() and zendAction(). Tweaked handleAction() of
                     controller to support 2 actions name styntax: 
                     ex: _index() or indexAction().
                     Note that _index() syntax have priority over indexAction() syntax.
 - Peak_Core > tweaked isController() and isModules() to check for the files instead of listing all controllers.
               It speedup application controllers/modules routing. Controllers and modules still can be listed on demand
 - Peak_View > fixed issue with setRenderEngine() when calling unknow rendering engine.
               Peak_View_Render_Partials is now loaded by default if no engine found.
 - Peak_View_Helpers class renamed to singular as the same for /helpers/ folder
 - Peak_View > added magic method __unset()
 - Peak_View_Render_Partials > fix render() to allow inclusion of partials scripts inside view 
                               without specifying the whole path
 - Peak_Dispatcher > added method reset(), getActions(), getRecursivity(), getAcceptedGlobals(),
                     getRecursivityDepth() and getActionsTriggered().
                     fixed method _listActions() : flush _actions array before retreiving them
 - autoload.php > added application custom loading class. 
                  Classes names mimic the directory structure that Peak use but under your application folder.
                  Ex: class Application_Models_User    = file: application/yourapp/models/user.php,
                      class Application_Libs_Tools_Xyz = file: application/yourapp/libs/tools/xyz.php,...
VERSION 0.7.8 

 - Peak_Application > fixed modules class load of method run()
 - Added Zend_Db to the framework
 - Peak_Bootstrap > added method getEnvironment() and support 'APPLICATION_ENV' inside from .htaccess
 - added Peak_core_Codegen class > generate application code files
 - Peak_core > Introduce core extensions objects primarily to move methods not used inside the application running process 
               and by the way, ligthen Peak_Core file. Removed/moved methods getLang(), getConfigs(), checkConfigs() 
               into extensions , and added method ext() to access to them. Extensions objects are stored in $extensions var.
 - Peak_Core > added method getPaths
 - autoload.php > added 'Controller' suffix support for controllers files and 
   fixed Peak_Application run() method and Peak_Controller initController() method accordly.
   Controller files and classes names look likes 'foobarController.php'
 - Added Peak_Dispatcher
 - Peak_View > fix problem with __call(). Methods 'return' value/boolean was missing
 - Peak_Render > added compression to caching
 - Peak_Render > added methods : isCachedBlock(), getCacheBlock(), cacheBlockEnd().
   Those new functions help to cache a block of text/code inside views

VERSION 0.7.7

 - Peak_Render > added methods : genCacheId() and getCacheIdFile().
   enableCache() and disableCache() can be used inside controller and bootstrap.
   isCached() can be used inside controller.
 - Peak_Render > Added scripts views outputing cache.
                 added methods    : enableCache(), disableCache(), preOutput(), isCached()
                 added properties : $_use_cache, $_cache_expire, $_cache_path, $_cache_id
                 Render engines must use preOutput() in their render() method instead of output() directly 
                 if they wants to support outputing cache. 
                 method output() must be protected
 - Peak_Registry > added static method isInstanceOf()
 - Peak_Lang > added method load()
 - Peak_Lang > method translate() > added translation string replacements and callback function
 - Peek_Application > fixed run(), constants ENABLE_PEAK_CONTROLLER were not properly checked. Also
   constant ENABLE_PEAK_CONTROLLER becomes ENABLE_PEAK_CONTROLLERS (now with a 'S' at the end)
 - Peak_Lang > remove property $varname and we now use return array('key' => 'translation'); in translation files 
 - CONSTANTS names change: SYSTEM_*** become LIBRARY_***, W_LANG -> APP_LANG, W_THEME -> APP_THEME, 
   W_DEFAULT_CTRL -> APP_DEFAULT_CTLR, W_LOGIN -> APP_LOGIN_NAME, W_PASS -> APP_LOGIN_PASS
 - Peak_Registry > added method get()
 - Added Peak_View_Render_Virtual
 - Peak_View > added __call() > try to look for unknow method in view engine object
   or trigger_error if method no found and DEV_MODE is true
 - Peak_Application > added support of peak internal controller if 
   constant ENABLE_PEAK_CONTROLLER exists
   internal controllers paths: /Peak/Controller/Internal/
 - Peak_View_Render > added method baseUrl(). 
 - Peak_View_Render > added magic method __call() to silent unknow methods calls
   inside views files on production environment
 - Peak_Controller > fixed E_STRICT error with method helper()
 - Peak_view > fixed E_STRICT error with method helper()
 - Peak_View > method theme() > replace deprecated function "is_a" by 
   operator "instanceof" (E_STRICT)
 - Peak_View_Theme > added method __construct() and add call to setOptions()s
 - Peak_View > added method resetVars()
 - Peak_Contoller > added call to postRender() after render(). No more need to explicitly 
   to call it in your application index
 - Peak_View_Render_XXX > fixed a circular problem with render() since render() 
   includes views and views have access to $this->render. The problem came from
   render() behavior and affect all view rendering engines.
   Also Peak_View_RenderInterface deleted and replace by abstract class Peak_View_Render.
   __get() method added and point to Peak_View::__get()
   !IMPORTANT object $view in TEMPLATE will be deprecated in favor of $this.
    ex: $this->my_view_var instead $view->my_view_var
   All view rendering engines needs:
   -render(): - should always authenticate files or throw Peak_Exception if 
                we need the controller script view, otherwise we just skip it
                since content is generated inside this method(JSON engine as
                concrete example).
              - set properties $scripts_file and $script_paths
              - return a valid string or an array() of views filepaths
   -output(): - should be private
              - includes view(s) filepath(s) only OR output render() content result
 - Peak_view > Seperated the logic behind rendering and outputing of views by 
               compartmentalize them into differents render engine               
               Introduce methods engine() and setRenderEngine().
               Long short story, we can control how we render each of our 
               application controllers actions views. For now we have
               Partials, Layouts, Json engines. 

VERSION 0.7.6

 - Peak_View_Theme > renamed method iniOptions() to set setOptions()
 - Peak_View > moved $options, getOptions(), getFunctionsFiles(), iniOptions() to new
   object Peak_View_Theme. We access to Peak_View_Theme methods with Peak_View::theme() 
 - Peak_View > removed useLayout(), noLayout(), getLayoutName(), $layout, $layout_name.
   !IMPORTANT
   View rendering is done by a render engine object ( Peak/View/Render/ ).
   - Method render() point to view engine render().
   - Method engine() added and point to view rendering object
   - Introduced View Engine Partials.php as default view rendering engine.
   Application now view support multiple themes with multiple partials and layouts pages
   Application helpers names have priority over Peak/View/Helpers
 - Peak_Controller > removed property $url_path > deprecated since 
   application dir should be outside public url
 - PROJECT WYNPHP renamed to PEAK Framework
   - all files have been renamed and restructured. The same for classes name
   - wystem folder renamed to peak
   - switch autoload to spl autoload. Classes files and names follow the path scheme like
     zend framework class : Peak_View_Helpers > Peak/View/Helpers.php
                            Peak_View         > Peak/View.php
 - class.registry.php > passed property $registered_objects to static and all method too.
   You can use now every method as static and still able to use the class as singleton
   added method set(), it do the same as register() method.
   register() is deprecated and removed, use set() instead
 - added class.wyn_bootstrap.php > abstract class base for application 
   root bootstrap.php file. Bootstrap are optionnal and will be loaded
   and executed only if file [application_folder]/bootstrap.php exists
 - class.wyn_controller.php > method listActions() > moved $regexp outside the preg_math loop
 - class.wyn_view.php > fixed bug with iniVars() > var with multiples constants 
   were not fully replaced
 - autoload.php > moved top code unrelated to autoload to boot.php.
   boot.php code are for managing critical application configs,
   starting framework and autoloader
   !IMPORTANT: app page changes : load app configs and core/boot.php
   and thats it! you ready to use app object to run your app   
 - Application configs simplified:
   - Only 13 configs now. 3 of them are optionnal.
   - Renamed almost very constants name to more explicit,
     with fewer abbreviations name
 - class.wyn_core.php > removed all $path properties and replaced them by $paths array.
   setPath() now generate constants and no more include core/constants.php
 - class.wyn_controller.php > change visibility to "protected" for property $view 
 - to avoid sessions collusion with multiple application,
   app index.php need to specified a session_name() before session_start
 - class.wyn_controller.php > removed property $wyn, removed $ctrl_type param in _construct,
   and fixed initController() to use router object. 
   Renamed method handleRequest() to handleAction()
   Renamed property $request to $params

VERSION 0.7.5

 - class.registry.php > added method getObjectList()
 - class.wyn.php > !IMPORTANT Replaced route() method by new method run().
   Removed methods getRequestURI(), setRequest(), getRequest() 
   and property $request. They are deprecated with the new router class.  
 - class.router.php > fixed preg_match regex in method getRequestURI()
 - class.wyn_view.php > moved all bottom file functions to functions.wyn_view.php
 - beginning changes that bring class.router.php inside class wyn and wyn_controller.
   In VERSION 0.7.6, changes implied by the new router object will be resolved
 - added simple url rewriting router > class.router.php 
   added router object to the registry in class.wyn.php
   @test unit: tests/router.php
   This new class allows us to use url like /controller/action/param1 and still permitS the use of 
   non-rewrited url like index.php?controller=action&param1
   Requirements Rewriting url: 
      - apache mod_rewrite
      - valid .htaccess that points to application public page @see class.router.php
   !IMPORTANT:
   All routing mechanism will be moved the router object
   class.wyn.php will only handle the route object and in the future will be the
   application launcher object
   This affect : - class.wyn.php methods > route(), 
                                           getRequest(), 
                                           getRequestUri(),
                                           handleInternRequest()
                 - class.wyn_controller > method handleRequest()
 - class.wyn_core.php > fix problem with getPath() not being declared as static method
 - class.wyn_core.php > method getController() > remove dependency 
   to class utils::fileremoveext() 
 - !IMPORTANT Added Registry object class (class.registry.php).  
   We can now store any objects from anywhere and put them somewhere else easily
   - objects wyn_core, wyn_view, lang removed from class.wyn.php :
     $wyn->core, $wyn->view and $wyn->lang objects DON'T exists anymore
   - to get registered object we use : registry::obj->['object registered name']
   - class.wyn.php load registry and store objects wyn_core, wyn_view and lang
   - file affected by change: class.wyn_controller.php, class.wyn_core.php, 
     class.wyn.php, class.viewhelper.php and everywhere we found $wyn->view, $wyn_core, etc
   - fixed class.wyn_view.php bottom functions to use registry instead of global var
   - tests unit file > tests/registry.php
   - current controller object still stored in class.wyn.php (ex: $wyn->controller->name)
 - class.pattern.php > added method range()
 - class.wyn.php > fixed method route() with new core method isModule() and getModule()
 - class.pattern.php > fixed some validation problem with day(), month(), year(), url()
   @see tests/pattern.php
 - create somes tests unit for wyn application start and wyn system classes

VERSION 0.7.4

 - added php tests for wyn with SimpleTest1.0.1 php unit tester
 - class.wyn_view.php > added method getOptions()
 - class.wyn.php > removed unnecessary properties $modules and $controllers, they are already 
   stored in wyn_core object. They are accessible via core methods 
   getModules() and getControllers()
 - class.wyn_core.php > added method getModule()
 - class.wyn_core.php > added exceptions to getControllers() if no controllers exists
 - class.wyn_controller.php > deprecated methods setVar(), getVar(), view()
   view object is now stored in $view
   ex: old way : $this->setVar('varname', $value);
       new way : $this->view->varname = $value;
 - class.wyn.php > renamed getRequest() to setRequest(), added method getRequest() and
   $request visibility changed to protected
 - renamed constants W_LIB_... to W_LIBS_...
 - !MARJOR CHANGES!
   application path and system path complete separation achieved started in 0.7.3:
   - autoloader.php call wyn_core::setPath() if wyn_core class is not loaded
   - removed params $app_path and $sys_path of method getInstance() > class.wyn.php
   - all application and system path vars and constants are generated from class wyn_core.php  
   - application page only needs to include configs.php file and core/autoload.php
   - now we can create unlimited applications with the same wyn system dirs
 - almost all path constant in configs.php have been moved to core/constants.php.
   configs.php is now more short and sweet

VERSION 0.7.3

 - class.wyn_core.php > moved here constants W_VIEWSCRIPTS_ABSPATH,
   W_VIEWINI_ABSPATH, W_CACHE_ABSPATH
 - class.lang.php > fixed a bug in __construct()
 - class.wyn.php > method getInstance() > added $app_path param 
   to propage it to wyn_core->setApplicationPath()
 - class.wyn_core.php > added methods setApplicationPath() and getPath().
   by creating and using those methods, we free the core from configs.php
   some absolute path constants dependencies. 
   For now only applcation, controllers and modules, lang path are supported
   In future, wyn_core will generate absolute path constants contained 
   in configs.php following its own file structure by only specifying an application path.
   No constants will be lost and we will be able to simplify and lighten configs.php
 - class.wyn_core.php > added methods isController() and isModule()
 - class.utils.php > added method objectSort() and objectRSort()
 - class.wyn_view.php > added properties $layout_name and method getLayoutName()
 - class.wyn_core.php > added method getModule() and listCoreClasses()
 - autoload.php > function catch_autoload_err() > added trace file and line
 - renamed core/class.wyn_utils.php to core/class.utils.php
 - added core/class.zreflection.php > Zend_Reflection_Class wrapper. 
   (use libs/zend/reflection/... )

VERSION 0.7.2

 - added core/class.pattern.php > Check different string pattern validation
 - class.wyn_view.php > moved method debug() to view helpers
 - added class.wyn_viewhelper.php > abstract class base for view helper classes,
   it add $view object reference for using inside view helpers 
   to update/change/remove view vars directly
 - class.wyn_controller.php > removed $log stuff and preAction() call moved to handleRequest()
 - class.wyn_controller.php > added postRender() method to fix postAction(). 
   postAction() was called after view rendering and now it is postRender() job.
   postAction() is now called after controller requested action.
 - class.wyn_controller.php > added view() method > return $wyn->view object
 - class.wyn_view.php > method render() > layouts accept now custom file 
   layout with path if file exists
   by default : 
     array('header.php',[CONTENT],'footer.php') 
     => W_TPL_ABSPATH/header.php, etc.
   custom:
     array('header.php','custompath/test.php',[CONTENT],'sidebar.php')
     => W_TPL_ABSPATH/header.php, 'custompath/test.php',etc
 - class.wyn_core.php > method getConfigs() > added $nologin bool param to 
   remove W_LOGIN and W_PASS for array of config
 - class.wyn_core.php > change visibility of var $w_modules and $w_controller to protected

VERSION 0.7.1

 - autoload.php > fixed _autoload() > after checking all class path, 
   if file is not found, it throw a trigger_error()
 - class.wyn.php > removed $this->controller->path overwrite calls in method route() since
   wyn_controller::initController() fix this behavior.
 - class.wyn_controller.php > lightened __construct() and moved code relative 
   to controller object variables initialization into initController() 
   __construct() accept $ctrl_type param as 'module' or 'controller'
 - class.wyn_controller.php > removed setControllerType() method, and removed calls in render()
 - class.wyn_view.php > moved links(),imgs() and cycle() methods to 
   functions.wyn_view.php wich is included in class.wyn_view.php
 - wyn.php, class.wyn_view.php > load theme 'functions.php',if file exists, before view print
   @see wyn_view > getFunctionsFile()
 - autoload.php > fixed a problem with ZEND_LIB_ABSPATH, and added internal zend library
   since wynphp will use zend reflection classes, it make sense to have the strict minimun:
   zend/reflection/ and zend/loader/ internally.
   __autoload() now check in first place if zend class needed exists in wsystem/libs/zend, 
   otherwise it checkS in W_ZENDLIB_ABSPATH (external zend library) if this config exists.
   and finally if the file is not found, it throw a trigger_error. 
 - class.wyn_view.php > $theme renamed to $options and iniTheme() 
   renamed to iniOptions()
   theme.ini arrays loaded into $options if file found.
   fixed useLayout() to accept strings that points to $options['layouts'][layout string]
 - added ZEND_LIB_ABSPATH in configs.php and modified autoload.php.
   We can use now Zend framework Library
   This setting is not essential, if this constant is not found, autoload
   will simply ignore it. 
 - class.wyn_core.php > added method getLang()

VERSION 0.7.0

 - added great shorcut functions in class.wyn_view.php for view object:
   view(), view_hlp(), view_get(), view_echo().
   ex: "echo view()->myvar" =  "view_echo('myvar')" = "echo $wyn->view->myvar"
       "view_hlp('text')->truncate($txt,10)" =
       "$wyn->view->heler('text')->truncate($txt,10)"
 - class.wyn_view.php > removed method handleRenderError(), 
   no more needed with class.wyn_exception.php
 - class.wyn_controller.php > fixed a bug with handleRequest().
   when _index() method doesn't exists, we throw a exception 
   instead of letting the controller trying to call an inexistant method
 - added layout possiblities in class.wyn_view.php, wyn.php. 
   See method useLayout() and noLayout() in wyn_view class for more info
   Folder 'theme' will be the 'pseudo layouts' folder and all others
   controller scripts views files have been moved in their respective view scripts folders
   /wsystem/view/scripts/[ctrl_name]/[action_name].php
   One big adavantage of this is to get rid of all thoses
   <?php include(W_TPL_ABASPATH.'/[header|footer|...].php'); ?> 
   in every controller views and modules views files 
   For example: we create a new controller called 'imanewctrl' so
   controller class = /wsystem/controllers/imanewctrl.php
   controller view default = /wsystem/view/scripts/imanewctrl/index.php
   Method action name in the controller are used for view file name
   ex: imanewctrl.php > action method = _myaction() 
       view file = /wsystem/view/scripts/imanewctrl/myaction.php 
 - class.wyn_controller.php > added function setControllerType() and variable $type.
   modified action name by default to _index instead of _default
 - added view helper text.php with miscellenous functions taken form smarty modifier
   view/helpers/text.php codes from http://smarty.net modifiers
 - updated class.xml.php > support of file_get_content and curl(usefull when 'allow_furl_open' is desactivated)
 - autoload.php > added catch_autoload_err() function.
   if the class name is not found in loaded file class
   we throw a custom error instead of letting php standard error handler.
   Support 'DEV_MODE'
 - wyn.php > exceptions point now to wexception controller
 - class.wyn.php > route() : modules and controller are now verified and throw wyn_exception if failed
 - added /core/class.wyn_exception.php

VERSION 0.6.9

 - class.wyn_controller.php > moved checkAlert() to wyn_core class 
   and renamed it checkConfigs()
 - class.wyn_controller.php > moved handleRenderError() to wyn_view class
 - class.wyn_controller.php >
   most of render() code copied to wyn_view, 
   but we still call the controller render() method basicly for controller 
   pre-render stuff and we let wyn_view class do what it suppose to do: 
   deal with template file verification and stuff like that.
 - class.wyn.php > $tpl object name renamed to $view.
   Important: use $this->wyn->view->[..] or $wyn->wyn->view->[..] in 
   controller and template. Quick var $tpl->[..] is style available in template 
   but no longrt since i renamed $tpl object to $view.
 - class.wyn_router.php > $module renamed to $controller
 - moved /wsystem/core/view/ folder to /wsystem/view/ 
 - fixed wyn_core.php: getControllers() > controllers were not properly cached into session
 - moved all action methods unrelated inside dashboard to controllers classes folder
 - added controllers heleprs /wsystem/controllers/helpers/
   class.wyn_controller.php > added helper() method
   ex: $this->helper('myCtrlHelper')->myfunc();
 - fix class.wyn_controller.php > render() to support wyn controllers folder
   render priority : wdashboard controller, [controller], [modules]

VERSION 0.6.8

 - fix autoload.php: added controllers class detection
 - added /controllers/ folders and move all controllers in /core/ there.
 - added view ini for preloading template variables(default.ini)
 - configs.php: added constants W_VIEWHELPERS_ROOT, W_VIEWINI_ROOT, W_VIEWHELPERS_ABSPATH, 
   W_VIEWINI_ABSPATH
 - fix class wlang problem in wyn.php and class.wyn.php. 
   The class was runned twice and not properly encapsulated in router class.wyn.php 
 - tweaked wyn_view.php: helper() > Syntax accepted now in template:
   $tpl->helper('myhelper')->myfunc(); //load helper and call func
   $tpl->helper()->myhelper->myfunc(); //only call already loaded helper func
 - dashboard class > added some infos on current apache 
 - param 'php_ext' added in moduled ini file. 
   Each module ini can now specify requires php extension(s) to run properly.
 - param 'descr' added in moduled ini file.
 - fix problem with debug window still available on login page
 - default template file of controller action method is view.[method].php 
   if $file is not specified
 - dashboard -> added 'about' action

VERSION 0.6.7

 - added view helpers. They are stored in core/helpers/view.[title].php
   ex:  view.calendar.php  - $tpl->helper('calendar');
 - wyn controller (class.intro.php) name renamed to dashboard(class.dashboard.php)
 - default controller action method name is now '_defaut' instead '_intro'