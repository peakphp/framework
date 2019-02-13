VERSION 4.0-BETA4
-----------
Release date: ?

 - added Http\Request\RouteExpression and updated Http\Request\Route to use it
 - added default param to Http\Request\RouteServerRequest::getParam()

VERSION 4.0-BETA3
-----------
Release date: 2019-02-12

 - removed trailing slash on request path in Http\Request\Route 
 - adding support of curly braces syntax for route parameters in Http\Request\Route 
 - added Http\Request\RouteParameter and updated Http\Request\Route to use it
 - added Http\Request\RouteServerRequest
 - added Common\Trait\MicroTime
 - refactored Common\Chrono
 - removed deprecated Common\ServiceLocator

VERSION 4.0-BETA2
-----------
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

VERSION 4.0-BETA1
-----------
Release date: 2019-01-24

 - first draft of v4