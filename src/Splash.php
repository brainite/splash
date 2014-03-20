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
      parent::append(new \ArrayIterator(array(
        $var,
      )));
    }
    return $this;
  }

  public function __call($name, $args) {
    $name .= 'Iterator';
    $class = strtolower($name);
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
          $ret = new $name($this->getInnerIterator());
          break;
        case 1;
          $ret = new $name($this->getInnerIterator(), $args[0]);
          break;
        case 2;
          $ret = new $name($this->getInnerIterator(), $args[0], $args[1]);
          break;
        case 3;
          $ret = new $name($this->getInnerIterator(), $args[0], $args[1], $args[2]);
          break;
      }
      return Splash::go()->append($ret);
    }
    else {
      $ret = Splash::go();
      switch (sizeof($args)) {
        case 0:
          foreach ($this->getInnerIterator() as $v) {
            $ret->append(new $name($v));
          }
          break;
        case 1;
          foreach ($this->getInnerIterator() as $v) {
            $ret->append(new $name($v, $args[0]));
          }
          break;
        case 2;
          foreach ($this->getInnerIterator() as $v) {
            $ret->append(new $name($v, $args[0], $args[1]));
          }
          break;
        case 3;
          foreach ($this->getInnerIterator() as $v) {
            $ret->append(new $name($v, $args[0], $args[1], $args[2]));
          }
          break;
      }
      return $ret;
    }
  }

}