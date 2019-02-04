VERSION 4.0-BETA2
-----------
Release date: ?

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
 - added method createStack() in Bedrock\Application\ApplicationG

VERSION 4.0-BETA1
-----------
Release date: 2019-01-24

 - first draft of v4