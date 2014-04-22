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

class SliceIterator extends \FilterIterator {
  public function __construct($it, $offset, $length = NULL, $preserve_keys = FALSE) {
    $arr = iterator_to_array($it, TRUE);
    $arr = array_slice($arr, $offset, $length, $preserve_keys);
    parent::__construct(new \ArrayIterator($arr));
  }

  public function accept() {
    return TRUE;
  }

}