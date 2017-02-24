<?php
/*
 * This file is part of the Splash package.
 *
 * (c) Greg Payne
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash;

class Splash extends \AppendIterator {
  static public function go() {
    $s = new Splash();
    return func_num_args() ? $s->appendArray(func_get_args()) : $s;
  }

  static public function fromArray() {
    $s = new Splash();
    foreach (func_get_args() as $arg) {
      $s->pushArray($arg);
    }
    return $s;
  }

  /**
   * Add an iterator or a value.
   * @param mixed $var
   * @return \Splash\Splash
   */
  public function &push($var) {
    if (is_object($var) && in_array('Iterator', class_implements($var))) {
      return $this->append($var);
    }
    else {
      return $this->append(new \ArrayIterator(func_get_args()));
    }
  }

  /**
   * Add an array of values
   * @param array $array
   * @return \Splash\Splash
   */
  public function pushArray($array) {
    return $this->append(new \ArrayIterator($array));
  }

  /**
   * Append an iterator
   * @see AppendIterator::append()
   */
  public function &append(\Iterator $it) {
    parent::append($it);
    return $this;
  }

  /**
   * Count the elements in the current iterator.
   * @return number
   */
  public function count() {
    return iterator_count($this);
  }

  /**
   * Magic method to map calls to the appropriate iterators.
   * @param string $name
   * @param array $args
   * @return \Splash\Splash
   */
  public function __call($name, $args) {
    if (method_exists("\\AppendIterator", $name)) {
      switch (sizeof($args)) {
        case 0:
          return parent::$name();
        case 1:
          return parent::$name($args[0]);
        case 2:
          return parent::$name($args[0], $args[1]);
        case 3:
          return parent::$name($args[0], $args[1], $args[2]);
      }
    }

    // Look for a custom iterator configuration.
    $custom = SplashRegistry::go()->getIteratorClass($name);
    if ($custom) {
      $name = $custom;
    }
    else {
      $name .= 'Iterator';
    }

    // Look for an append operation.
    if (substr($name, 0, 6) === 'append') {
      $name = substr($name, 6);
      switch (sizeof($args)) {
        case 0:
          return $this->append(new $name());
        case 1;
          return $this->append(new $name($args[0]));
        case 2;
          return $this->append(new $name($args[0], $args[1]));
        case 3;
          return $this->append(new $name($args[0], $args[1], $args[2]));
      }
    }
    elseif (is_subclass_of($name, 'FilterIterator')) {
      // Avoid a deep recursion of iterators.
      // In unit tests, this caused duplicate results when a regex was applied to a recursivedirectory.
      // This is symptomatically similar to a known AppendIterator bug:
      // https://bugs.php.net/bug.php?id=49104
      $ret = Splash::go();
      switch (sizeof($args)) {
        case 0:
          $ret->append(new $name(new \ArrayIterator($this->toArray())));
          break;
        case 1;
          $ret->append(new $name(new \ArrayIterator($this->toArray()), $args[0]));
          break;
        case 2;
          $ret->append(new $name(new \ArrayIterator($this->toArray()), $args[0], $args[1]));
          break;
        case 3;
          $ret->append(new $name(new \ArrayIterator($this->toArray()), $args[0], $args[1], $args[2]));
          break;
      }
      $ret->rewind();
      return $ret;
    }
    elseif (stripos($name, 'IteratorIterator') !== FALSE) {
      $ret = Splash::go();
      switch (sizeof($args)) {
        case 0:
          foreach ($this->getArrayIterator() as $it) {
            $ret->append(new $name($it));
          }
          break;
        case 1;
          foreach ($this->getArrayIterator() as $it) {
            $ret->append(new $name($it, $args[0]));
          }
          break;
        case 2;
          foreach ($this->getArrayIterator() as $it) {
            $ret->append(new $name($it, $args[0], $args[1]));
          }
          break;
        case 3;
          foreach ($this->getArrayIterator() as $it) {
            $ret->append(new $name($it, $args[0], $args[1], $args[2]));
          }
          break;
      }
      return $ret;
    }
    elseif (class_exists($name)) {
      // NOTE: The iterator_to_array casting below improves handling by HHVM.
      $ret = Splash::go();
      switch (sizeof($args)) {
        case 0:
          foreach ($this->toArray() as $v) {
            $ret->append(new $name($v));
          }
          break;
        case 1;
          foreach ($this->toArray() as $v) {
            $ret->append(new $name($v, $args[0]));
          }
          break;
        case 2;
          foreach ($this->toArray() as $v) {
            $ret->append(new $name($v, $args[0], $args[1]));
          }
          break;
        case 3;
          foreach ($this->toArray() as $v) {
            $ret->append(new $name($v, $args[0], $args[1], $args[2]));
          }
          break;
      }
      return $ret;
    }
    else {
      throw new \ErrorException("Splash method call does not correspond to a valid iterator class.");
    }
  }

  /**
   * Create the \splash() global shorthand method for quick access to Splash.
   */
  public static function mount() {
    if (!function_exists('splash')) {
      eval("function splash() { \$s = \\Splash\\Splash::go(); return func_num_args() ? \$s->appendArray(func_get_args()) : \$s; }");
    }
  }

  public function unique() {
    return Splash::go()->append(new \ArrayIterator(array_unique($this->toArray())));
  }

  public function toArray() {
    $this->rewind();
    $item = $this->current();

    // DirectoryIterator cannot use iterator_to_array.
    // https://bugs.php.net/bug.php?id=49755
    if ($item instanceof \DirectoryIterator) {
      $arr = array();
      foreach ($this as $v) {
        $arr[] = new \SplFileInfo($v->getPathname());
      }
      return $arr;
    }
    else {
      return iterator_to_array($this);
    }
  }

  public function toStringImplode($glue = '') {
    return implode($glue, $this->toArray());
  }

}