Splash
======

Tested against PHP 5.6/7.1/HHVM

[![Build Status](https://travis-ci.org/brainite/splash.png?branch=master)](https://travis-ci.org/brainite/splash)

Splash is a chainable (and therefore concise) way to interact with the SPL iterators.
The <a href="http://www.php.net/manual/en/intro.spl.php">Standard PHP Library (SPL)</a>
provides various utility classes and interfaces to address common problems.
Some of the most visible solutions center around the 
<a href="http://www.php.net/manual/en/spl.iterators.php">SPL Iterators</a>.
The ability to iterate over a non-array collection and then to iterate over 
that iterator creates an interesting design pattern for building with PHP. 
However, the iterators deprioritize conciseness and therefore (to some degree) readability.

## Example Default SPL Iterator Usage

This example is extracted from the <a href="http://www.php.net/manual/en/class.recursivedirectoryiterator.php">PHP manual</a>.

```php 
$objects = new RegexIterator(new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST),
  '/^.+\.php$/i');
foreach ($objects as $object) {}
```

As with any non-chainable procedural code, you have to read this from the inside out in an unnatural and error-prone way.

## Example Splash Alternative

```php
\Splash\Splash::mount();
$objects = splash($path)->recursiveDirectory()
  ->recursiveIterator(RecursiveIteratorIterator::SELF_FIRST)
  ->regex('/^.+\.php$/i');
foreach ($objects as $object) {}
```

The value of $object should be identical within the foreach loop compared to the approach above. However, the layers of iterators and their corresponding constructor values are now organized linearly and with less code to improve readability. Ultimately, that is the singular purpose of Splash - to leverage the benefits of chaining in the context of Iterators.

## Getting Started

1. Install using composer: <a href="https://packagist.org/packages/brainite/splash">brainite/splash</a>.
1. Create splash objects via one of these methods:
   1. `$splash = new \Splash\Splash();` // This is a basic approach.
   1. `$splash = \Splash\Splash::go();` // This returns a Splash object that allows you to immediately chain.
   1. `\Splash\Splash::mount();` // This makes the \splash() global function available for you to use for even more succinct coding. It creates a Splash object, and any arguments are added to an ArrayIterator that is thrown into the initial object via the push() method.

## Equivalent examples:

### Option 1

```php
use Splash\Splash;
$test = new Splash();
$test->push(1)->push(2)->push(3);
```

### Option 2

```php
use Splash\Splash;
$test = Splash::go()->push(1)->push(2)->push(3);
```

### Option 3

```php 
use Splash\Splash;
$test = Splash::go()->push(1, 2, 3);
```

### Option 4

```php
\Splash\Splash::mount();
$test = splash(1, 2, 3);
```

<a href="https://github.com/brainite/splash/tree/master/src/Tests">For more examples, take a look at the unit tests.</a>

Splash Custom Iterators
-----------------------

Splash comes bundles with some iterators that did not make it into SPL. Currently, this includes:

### [InverseRegexIterator](https://github.com/brainite/splash/blob/master/src/Iterator/InverseRegexIterator.php)

This is a simple iterator that excludes any items that match a regular expression.

```` php
$dat = splash('a', 'b')->inverseRegex('/a/')->toArray();
// $dat == array('b')
````

### [SliceIterator](https://github.com/brainite/splash/blob/master/src/Iterator/SliceIterator.php)

This is a simple iterator that narrows results to a slice like array_slice().

```` php
$dat = splash('a', 'b', 'c')->slice(1, 1)->toArray();
// $dat == array('b')
````

### [CallbackIterator](https://github.com/brainite/splash/blob/master/src/Iterator/CallbackIterator.php)

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
