---
title: SPLash
---

The <a href="http://www.php.net/manual/en/intro.spl.php">Standard PHP Library (SPL)</a> provides various utility classes and interfaces to address common problems. Some of the most visible solutions center around the <a href="http://www.php.net/manual/en/spl.iterators.php">SPL Iterators</a>. The ability to iterate over a non-array collection and then to iterate over that iterator creates an interesting design pattern for building with PHP. However, the iterators deprioritize conciseness and therefore (to some degree) readability.

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
$objects = splash($path)-&gt;recursiveDirectory()
  -&gt;recursiveIterator(RecursiveIteratorIterator::SELF_FIRST)
  -&gt;regex('/^.+\.php$/i');
foreach ($objects as $object) {}
```

The value of $object should be identical within the foreach loop compared to the approach above. However, the layers of iterators and their corresponding constructor values are now organized linearly and with less code to improve readability. Ultimately, that is the singular purpose of Splash - to leverage the benefits of chaining in the context of Iterators.

## Getting Started

1. Install using composer: <a href="https://packagist.org/packages/wittiws/splash">wittiws/splash</a>.
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
