Splash
======

Tested against PHP 5.3/5.4/5.5/HHVM

[![Build Status](https://travis-ci.org/wittiws/splash.png?branch=master)](https://travis-ci.org/wittiws/splash)

Splash is a chainable (and therefore concise) way to interact with the SPL iterators.

```` php
// Basic example.
\Splash\Splash::mount();
foreach (splash(__DIR__)->recursiveDirectory()->regex($match) as $path) {
}
````

[For more information, visit the project page.](http://www.witti.ws/project/splash)

Splash Custom Iterators
-----------------------

Splash comes bundles with some iterators that did not make it into SPL. Currently, this includes:

### [InverseRegexIterator](https://github.com/wittiws/splash/blob/master/src/Iterator/InverseRegexIterator.php)

This is a simple iterator that excludes any items that match a regular expression.

```` php
$dat = splash('a', 'b')->inverseRegex('/a/')->toArray();
// $dat == array('b')
````

### [SliceIterator](https://github.com/wittiws/splash/blob/master/src/Iterator/SliceIterator.php)

This is a simple iterator that narrows results to a slice like array_slice().

```` php
$dat = splash('a', 'b', 'c')->slice(1, 1)->toArray();
// $dat == array('b')
````
