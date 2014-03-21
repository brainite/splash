<?php
namespace Splash;

class Splash extends \AppendIterator {
  public static function go() {
    return new Splash();
  }

  /**
   * Add an iterator or a value.
   * @param mixed $var
   * @return \Splash\Splash
   */
  public function append($var) {
    if (is_object($var) && in_array('Iterator', class_implements($var))) {
      parent::append($var);
    }
    else {
      parent::append(new \ArrayIterator(func_get_args()));
    }
    return $this;
  }

  /**
   * Magic method to map calls to the appropriate iterators.
   * @param string $name
   * @param array $args
   * @return \Splash\Splash
   */
  public function __call($name, $args) {
    $name .= 'Iterator';
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
      $ret = NULL;
      switch (sizeof($args)) {
        case 0:
          $ret = new $name($this);
          break;
        case 1;
          $ret = new $name($this, $args[0]);
          break;
        case 2;
          $ret = new $name($this, $args[0], $args[1]);
          break;
        case 3;
          $ret = new $name($this, $args[0], $args[1], $args[2]);
          break;
      }
      return Splash::go()->append($ret);
    }
    else {
      $ret = Splash::go();
      switch (sizeof($args)) {
        case 0:
          foreach ($this as $v) {
            $ret->append(new $name($v));
          }
          break;
        case 1;
          foreach ($this as $v) {
            $ret->append(new $name($v, $args[0]));
          }
          break;
        case 2;
          foreach ($this as $v) {
            $ret->append(new $name($v, $args[0], $args[1]));
          }
          break;
        case 3;
          foreach ($this as $v) {
            $ret->append(new $name($v, $args[0], $args[1], $args[2]));
          }
          break;
      }
      return $ret;
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
    return Splash::go()->append(new \ArrayIterator(array_unique(iterator_to_array($this))));
  }

}