# JsonObjectMapper
A small class used to map any kind of object into JSON

# How to use this class?
1) Import the class in your project

2) Call `mapObjectToJson` or `mapObjectsToJson` with your object(s) in parameter

3) That's all.  Use the output as you like :)  If you want more advanced usages, please continue reading this file.

### Example
``` 
$object = new MyOwnObject();
//Make something with $object here
//Example:
$object->setId(23);
$object->setName('Hello world');
//Get JSON output
$jsonMapper = new JsonObjectMapper();
$jsonOutput = $jsonMapper->mapObjectAsJson($object);
```


You can also add some filters to export only some value or block some values.

### Example:
```
$object; //Object you've created before
//Let's export ONLY the name
$jsonMapper = new JsonObjectMapper();
$jsonOutput = $jsonMapper->mapObjectAsJson($object, array('name'));  //NOTE : 'getName' is also valid but 'getname' will be ignored
```

### Example 2:
```
$object; //Object you've created before
//Let's export everything EXCEPT Object ID
$jsonMapper = new JsonObjectMapper();
$jsonOutput = $jsonMapper->mapObjectAsJson($object, array(), array('id')); //NOTE: just like example before, 'getId' is also valid
```

## Work with an array
You can also send an array of objects to _JsonObjectMapper_  It works exactly the same, except first parameter is an array.  Please note method name is *plural*.

### Example:
```
$objectsList = array();
//Add some objects to the list
//And map them into JSON
$jsonMapper = new JsonObjectMapper();
$jsonOutput = $jsonMapper->mapObjectsAsJson($objectsList); //WARNING: method name is «mapObjectS» with an «S».  Be careful ;)
```

As for a single object, you can filter the output with allowed/prohibited methods.  These filters will be applied to all objects mapped.

### Example:
```
$objectsList = array();
//Add some objects to the list
//And map them into JSON
$jsonMapper = new JsonObjectMapper();
$jsonOutput = $jsonMapper->mapObjectsAsJson($objectsList, array(), array('id')); //Allow everything except ID
```
