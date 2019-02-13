# ArrayContainer

[![Build Status](https://travis-ci.org/AndyDune/ArrayContainer.svg?branch=master)](https://travis-ci.org/AndyDune/ArrayContainer)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/andydune/array-container.svg?style=flat-square)](https://packagist.org/packages/andydune/array-container)
[![Total Downloads](https://img.shields.io/packagist/dt/andydune/array-container.svg?style=flat-square)](https://packagist.org/packages/andydune/array-container)


It offers convenient interface for encapsulated array. Implements strategy template for any number of filters.

Requirements
------------

- PHP version >= 7.1
- Sting "It's probably me" inside your headphones.

Installation
------------

Installation using composer:

```
composer require andydune/array-container
```
Or if composer was not installed globally:
```
php composer.phar require andydune/array-container
```
Or edit your `composer.json`:
```
"require" : {
     "andydune/array-container": "^1"
}

```
And execute command:
```
php composer.phar update
```


Simple access to array
------------

You do not need to worry about existence any key.

```php
use AndyDune\ArrayContainer\ArrayContainer;

$aray = new ArrayContainer(['pipe' => 'handemade', 'pipes_type' => ['lovat', 'canadian']]);
$array['pipe'] // value is 'handemade' 
$array['tobacco'] // value is null
$array->getNested('pipes_type.0')  // value is 'lovat'
$array->getNested('pipes_type:0', null, ':')  // value is 'lovat'
$array->getNested('some.some', 'NO')  // value is 'NO'

// Set default value
$array->setDefaultValue('NO');
$array['tobacco'] // value is 'NO'
```

Filters
------------

Filters is callable objects. Filters uses during request properties with getters.

```php
use AndyDune\ArrayContainer\ArrayContainer;
$aray = new ArrayContainer(['pipe' => 'handemade', 'pipes_type' => ['lovat', 'canadian']]);
$array->addFilter(function ($value) {
    return strtoupper($value);
});
$array['pipe'] // value is 'HANDEMADE'

```

Modifiers
------------

Modifier is a object of class witch implements AndyDune\ArrayContainer\Action\AbstractAction interface. 
It simple can be simple extented without modification main class. 

### Add keys to array if not exist.

There is source array:

```php
$array = [
    'type' => 'good'
];
```

You need to use it into model. Model wait array with keys: *type*, *key* and *value* 

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\KeysAddIfNoExist;
$container = new ArrayContainer();

$defaultValue = 0; // Set this value if array key does not exist.

$container->setAction(new KeysAddIfNoExist($defaultValue))->executeAction('type', 'key', 'value');
$resultArray = $container->getArrayCopy();
```

Result array is:
```php
$resultArray = [
    'type' => 'good',
    'key' => 0,
    'value' => 0
];
```

### Array shift maintaining key to data correlations.

There is source array with numeric keys.
```php
$arraySource = [
    40 => 'fourty',
    50 => 'figty',
    60 => 'sixty',
];
```

After execution function `array_shift` keys will be lost.
```php
array_shift($arraySource);
$arraySource = [
    0 => 'figty',
    1 => 'sixty',
];
```

Array container action helps to avoid it.

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\ArrayShift;

$arraySource = [
    40 => 'fourty',
    50 => 'figty',
    60 => 'sixty',
];

$container = new ArrayContainer($arraySource);

$result = $container->setAction(new ArrayShift())->executeAction();

$result == [40 => 'fourty'];

$resultArray =  $container->getArrayCopy();
```

Result array is: 

```php
[
    50 => 'figty',
    60 => 'sixty',
];
```

### Add value to nested array

We have array structure of witch we don't know. Need to set value to it's nested value with check of existence of nested structure. 
It changes only given array keys.

```php
// Sourse array:
$array = [
    'a' => 1,
    'b' => [
        'c' => 2
    ]
];
$container->setAction(new SetValueIntoNestedArray(22))->executeAction('b', 'cc');
$arrayResult = $container->getArrayCopy();

// Result array is:
$arrayResult = [
    'a' => 1,
    'b' => [
        'c' => 2,
        'cc' => 22
    ]
];

```

### Remove from array duplicated values

It needs simple to remove values witch duplicates. Here how we can do it.

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\RemoveDuplicates;

$array = [
    'a' => 'a',
    'b' => 'b',
    'b1' => 'b',
    'c' => 'c',
];
$container = new ArrayContainer($array);
$count = $container->setAction(new RemoveDuplicates())->executeAction();
$count == 1; // it is count of removed items
$array = $container->getArrayCopy());

$array; // it has no value with key b1

``` 


### Create new array with values from current array

It needs to create new list of values from any fixed list. The new list must contain random values.

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\ExtractRandomItems;

$array = [
    'a',
    'b',
    'c',
    'd',
    'e',
    'f'
];
$container = new ArrayContainer($array);
$arrayNew = $container->setAction(new ExtractRandomItems(3))->executeAction();
``` 

It leaves keys in new array as it was in source array.


Access array with path notation
------------

Access to array value (more if array is nested) may require validation and check. Path helps make it easily.

```php
use AndyDune\ArrayContainer\Path;
$arr = [
'key1' => 'bum',
'key2' => ['key21' => [
    'key211' => 'mub'
]],
];

// To get value with key `key211` you need:
$arr['key2']['key21']['key211'] // mub
// with Path 
$arrObject = new Path($arr);
(string)$arr->key2->key21->key211; // mub

```

Set value inside nested array:

```php
use AndyDune\ArrayContainer\Path;
$arrObject = new Path($arr);
$arr->key2->key21->key211 = 'bum';
 
(string)$arr->key2->key21->key211; // 'bum'
$arr->key2->key21->noExist_id->getValue(); // null

```
