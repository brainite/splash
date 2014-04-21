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

class InverseRegexIterator extends \RegexIterator {
  public function accept() {
    return !parent::accept();
  }

}