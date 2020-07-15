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

Filters are callable objects. Filters are used during request properties with getters.

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

Modifier is an object of class witch implements AndyDune\ArrayContainer\Action\AbstractAction interface. 
It can be simple extented without modification main class. 

### Add keys to array if not exist.

There is source array:

```php
$array = [
    'type' => 'good'
];
```

You need to use it inside model. Model waits array with keys: *type*, *key* and *value* 

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

It is source array with numeric keys.
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

Ome more example. 

We have stait array with month, year and counts of entities within this date.
```php
$data = [[
    'month' => 7,
    'year' => 2020,
    'orderCount' => 2,
    ],
],
[
    'month' => 1,
    'year' => 2020,
    'orderCount' => 20,
    ],
],

...
]
```
We need to recieve something like this:
```php
$result = [
    2007 => [
        1 => 2,
        7 => 20
    ]
]
```

Here is code for this:
```php
use AndyDune\ArrayContainer\Action\SetValueIntoNestedArray;
use AndyDune\ArrayContainer\ArrayContainer;

$arrayContainer = new ArrayContainer();
foreach($data as $row) {
    $arrayContainer->setAction(new SetValueIntoNestedArray($row['orderCount']))
        ->executeAction($row['year'], $row['month']);
}
$result = $arrayContainer->getArrayCopy();
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

### Check is value in nested array

There is nested value with any structure. It checks is some value in given array including values in nested arrays.

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\InNestedArray;

$array = [
    'a' => 1,
    'b' => [
        'c' => 2
    ]
];
$container = new ArrayContainer($array);
$container->setAction(new InNestedArray(1))->executeAction(); // true
$container->setAction(new InNestedArray('1'))->executeAction(); // true
$container->setAction(new InNestedArray(5))->executeAction(); // false

// With strong type comparision
$container->setAction(new InNestedArray('1', true))->executeAction(); // false
```

Values can be changed before comparision:

```php
$array = [
    [
        [
            'name' => 'Ivan'
        ],
        [
            'name' => 'Andrey'
        ],
    ]
];
$container = new ArrayContainer($array);

// false: Ivan != ivan
$container->setAction(new InNestedArray('ivan'))->executeAction(); 

// true: strtolower to values before compare
$container->setAction((new InNestedArray('ivan'))->setValueMutator(function($value){
    if (!is_string($value)) {
        return $value;
    }
    return strtolower($value);
}))->executeAction();

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

### Concat arrays

Function `array_merge` may act not right with associative arrays.

Example down next:

```php
$a1 = ['first' => 1, 'second' => 2];
$ar = array_merge($a1, ['second' => 22])

The result is:
$ar == ['first' => 1, 'second' => 22];
```

Concat action helps to do it right:

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\Concat;

$a1 = ['first' => 1, 'second' => 2];

$container = new ArrayContainer($a1);
$result = $container->setAction(new Concat())->executeAction(['second' => 22]);

The result is:
$ar == ['first' => 1, 'second' => 2, 22];
```

### Computes the difference of arrays with additional index check

Compares array1 against array2 and more and returns the difference. It computes arrays recursively.

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\ComputeDifferenceOfArrays;

$arrayWait = [
    'r' => 'red',
    'rr' => [
        'r1' => 'red1',
        'rrr' => [
            'r2' => 'red2',
            'r22' => ['red22']
        ],
        'r2' => 'red2',
    ],
    'b' => 'blue'
];

$container = new ArrayContainer($array);
$result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(
    ['r' => 'red', 'rr' => ['r1' => 'red1', 'rrr' => ['r22' => ['red22']]]]
);

// The resu is:

$arrayWait = [
    'rr' => [
        'rrr' => [
            'r2' => 'red2',
            'r22' => ['red22']
        ],
        'r2' => 'red2',
    ],
    'b' => 'blue'
];
```

It may ignore some keys within result.
```php
$container = new ArrayContainer($array);
$result = $container->setAction(
(new ComputeDifferenceOfArrays())->ignoreKeys('r2', ['b'])))->executeAction(
    ['r' => 'red', 'rr' => ['r1' => 'red1']]
);

// The resu is:

$arrayWait = [
    'rr' => [
        'rrr' => [
            'r22' => ['red22']
        ],
    ]
];

``` 

### Check is array has only fixed keys

It checks source array if it has only this keys.

```php
$array = [
    'r' => 'red',
    'rr' => [
        'r1' => 'red1',
    ],
    'b' => 'blue'
];
$container = new ArrayContainer($array);
$result = $container->setAction(new IsOnlyThisKeysInArray())->executeAction('r');
$result == false;

$container = new ArrayContainer($array);
$result = $container->setAction(new IsOnlyThisKeysInArray())->executeAction('r', ['rr']);
$result == false;

$container = new ArrayContainer($array);
$result = $container->setAction(new IsOnlyThisKeysInArray())->executeAction('r', ['rr', 'b']);
$result == true;
```

### Create integer array with values not in sequence

There is an array with integer values we have. Values are mixed, not in order and can be duplicated. 
We can receive array with values witch were skipped.

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\GetIntegerNumbersNotInSequence;

$array = [5, 1, 7, 8];
$container = new ArrayContainer($array);
$result = $container->setAction(new GetIntegerNumbersNotInSequence())->executeAction();
// result is:
[2,3,4,6] == $result;
```   

### Find max float value in array

It searches max float value in the given array. Each value is prepared by removing spaces.

```php
use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Action\FindMaxFloatValue;

$container = new ArrayContainer(['- 1', -2.56, 10, ' 1 1 ']);
$result = $container->setAction(new FindMaxFloatValue())->executeAction();
$this->assertEquals(11, $result);
// result is:
11 == $result;
```   


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


Build array
------------

## MultilineTextToAssociatedArray

It creates array from text with lines as key-values pairs in it.

```php
use AndyDune\ArrayContainer\Builder;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToAssociatedArray;

$sourceText = '
one => two
three
=> four

';

$expectResult = [
    'one' => 'two',
    'four',
    'three' => null
];

$builder = new Builder($sourceText, new MultilineTextToAssociatedArray('=>'));

// result is
$expectResult == $builder->execute();

```  

## MultilineTextAsJsonToAssociatedArray

It creates array from text with lines as json-like key-values pairs in it.

```php
use AndyDune\ArrayContainer\Builder;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextAsJsonToAssociatedArray;

$text = '
{
"one":"two",
"two" : 2,
"three":null
}

';

$expectResult = [
    'one' => 'two',
    'two' => 2,
    'three' => null
];

$builder = new Builder($text, new MultilineTextAsJsonToAssociatedArray());
// result is

$expectResult ==  $builder->execute();
```

## MarkdownTableToArray

It creates array from Markdown table.

Read about format [here](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet#tables)

```php
use AndyDune\ArrayContainer\Builder;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextAsJsonToAssociatedArray;

$text = '
| one | two | 
| --- | ---
1 | 2
11
| 12| 13 | 14
';

$expectResult = [
    [
        'one' => 1,
        'two' => 2
    ],
    [
        'one' => '11',
        'two' => null
    ],
    [
        'one' => '12',
        'two' => '13'
    ]
];

$builder = new Builder($text, new MarkdownTableToArray());
$expectResult == $builder->execute();
```

You can build not assoc array like this:

```php
use AndyDune\ArrayContainer\Builder;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextAsJsonToAssociatedArray;

$text = '
    | one | two | 
    1 | 2
    |
    || 5
    11
    | 12| 13 | 14
';

$expectResult = [
    ['one', 'two'],
    [1, 2],
    ['', 5],
    ['11',  null],
    ['12', '13']
];

$builder = new Builder($text, new MarkdownTableToArray());
$expectResult == $builder->execute();
```

## MultilineTextToNestedAssociatedArray

It creates array from text with lines as key-values pairs in it. Values are nested arrays.

```php
use AndyDune\ArrayContainer\Builder;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToNestedAssociatedArray;

$text = '
one > one, 
one > two, one
four > 4, 5, 6
';

$expectResult = [
    'one' => ['one', 'two'],
    'four' => [4, 5, 6],
];

$builder = new Builder($text, new MultilineTextToNestedAssociatedArray());
$expectResult == $builder->execute();
```


## StringExplode

It does simple string explode procedure with removing empty values or not.

```php
use AndyDune\ArrayContainer\Builder;
use AndyDune\ArrayContainer\BuilderStrategy\StringExplode;

$text = '
    one , two,
';

$expectResult = [
    'one', 'two'
];

$builder = new Builder($text, new StringExplode(','));
$expectResult == $builder->execute();
```
