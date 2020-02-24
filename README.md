Splash
======

Tested against PHP 5.3/5.4/5.5/HHVM

[![Build Status](https://travis-ci.org/brainite/splash.png?branch=master)](https://travis-ci.org/brainite/splash)

Splash is a chainable (and therefore concise) way to interact with the SPL iterators.

```` php
// Basic example.
\Splash\Splash::mount();
foreach (splash(__DIR__)->recursiveDirectory()->recursiveIterator()->regex($match) as $path) {
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

### [CallbackIterator](https://github.com/wittiws/splash/blob/master/src/Iterator/CallbackIterator.php)

This is an iterator that runs a callback against each element and allows the callback to either
return TRUE to retain values (like with CallbackFilterIterator) or to directly manipulate the
new iterator. The direct advanced option allows for the splitting of values via the callback.

```` php
/**
 * Callback for CallbackIterator
 *
 * @param $current   Current item's value
 * @param $key       Current item's key
 * @param $iterator  Iterator being traversed
 * @param $new_iterator Iterator being built (to allow item splits)
 * @return boolean   TRUE to auto-add the current item to $new_iterator, FALSE otherwise
 */
function my_callback(&$current, $key, $iterator, &$new_iterator) {
  $current = 'a';
  return TRUE;
}

$dat = splash('a', 'b', 'c')->callback('my_callback')->toArray();
// $dat == array('a', 'a', 'a')
````
