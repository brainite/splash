<?php
/*
 * This file is part of the Splash package.
 *
 * (c) Greg Payne
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Tests;
use Splash\Splash;

class CustomIteratorTest extends \PHPUnit_Framework_TestCase {
  /**
   * Test the InverseRegexIterator
   */
  public function testInverseRegex() {
    $data = array(
      'foo',
      'bar',
    );
    $expected = array(
      'foo',
    );
    $actual = splash()->appendArray($data)->inverseRegex('/bar/')->toArray();
    $this->assertEquals($expected, $actual);
  }

  /**
   * Test the SliceIterator
   */
  public function testSlice() {
    $expected = array(
      'bar',
      'bar2',
    );
    $actual = splash('foo', 'bar', 'bar2')->slice(1)->toArray();
    $this->assertEquals($expected, $actual);

    $expected = array(
      'bar',
    );
    $actual = splash('foo', 'bar', 'bar2')->slice(1, 1)->toArray();
    $this->assertEquals($expected, $actual);
  }

}