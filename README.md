# Mapper
A small class used to map any kind of object into JSON/array

# How to use this class?
1) Import the class in your project

2) Call `map`.  And that's it :)
##Prototype
```php
mixed map($object, MapMethod $method[, $allowedValues = array(), $rejectedValues = array()])
```
####Arguments
 1. Object to map.  Can be an object or an array of objects
 2. Return type you want.  **MUST** be a value of `MapMethods`  Currently, only `MapMethods::Array` and `MapMethods::JSON` are available. 
 3.  An array containing the list of values that should be mapped.  If anything is provided here, **only** these methods will be mapped.  Array can contain method's full name (ex: `getAttribute` ) or only attribute name (Ex: `attribute` ).  In the last case, make sure your getters are following the convention.
 4. An array containing the list of values that should **not** be mapped.  If `$allowedValues` is provided, this argument will be ignored.  Naming conventions are the same as for `$allowedValues` 
####Return value
Return value can be multiple.  It should be your desired type, such as *array* or *string* in JSON was requested.  A *bool* can also be returned in case of any problem were encountered, so be sure to check return value with `=== false` to avoid any surprise.

That's all.  Use the output as you like :)  If you want more advanced usages, please continue reading this file.

### Example
``` 
$object = new MyOwnObject();
//Make something with $object here
//Example:
$object->setId(23);
$object->setName('Hello world');
//Get JSON output
$objectMapper = new Mapper();
$jsonOutput = $objectMapper->map($object, MapMethods::JSON);
```


You can also add some filters to export only some value or block some values.

### Example:
```
$object; //Object you've created before
//Let's export ONLY the name
$objectMapper = new Mapper();
$jsonOutput = $objectMapper->map($object, MapMethods::JSON, array('name'));  //NOTE : 'getName' is also valid but 'getname' will be ignored
```

### Example 2:
```
$object; //Object you've created before
//Let's export everything EXCEPT Object ID
$objectMapper = new Mapper();
$jsonOutput = $objectMapper->map($object, MapMethods::JSON, array(), array('id')); //NOTE: just like example before, 'getId' is also valid
```
In this case, every attribute of your object will be exported, except the ID.

## Work with an array
You can also send an array of objects to _Mapper_  It works exactly the same, except first parameter is an array.

### Example:
```
$objectsList = array();
//Add some objects to the list
//And map them into JSON
$objectMapper = new Mapper();
$arrayOutput = $objectMapper->map($objectsList, MapMethods::Array);
```
As for a single object, you can filter the output with allowed/prohibited methods.  These filters will be applied to all objects mapped.
**NOTE** : If you send a single object to *Mapper* and request it to be mapped as an array, you'll receive a single array object containing all you object's attributes.  If you send an array of objects, you will receive an array of object's array.

### Example:
```
$objectsList = array();
//Add some objects to the list
//And map them into JSON
$objectMapper = new Mapper();
$jsonOutput = $objectMapper->map($objectsList, MapMethods::Array, array(), array('id')); //Allow everything except ID
```
