<?php
/*
 * This file is part of the Splash package.
 *
 * (c) Greg Payne
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Iterator;

class CallbackIterator extends \FilterIterator {
  public function __construct($it, $callback) {
    $new = new \ArrayIterator();
    foreach ($it as $k => $v) {
      if (TRUE === $callback($v, $k, $it, $new)) {
        $new->append($v);
      }
    }
    parent::__construct($new);
  }

  public function accept() {
    return TRUE;
  }

}