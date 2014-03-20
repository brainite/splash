<?php
namespace Splash\Tests;

use Splash\Splash;
class BasicTest extends \PHPUnit_Framework_TestCase {
  public function testFilesystem() {
    // The iterators should easily locate this file.
    $splash = Splash::go()->append(__DIR__);
    $match = '@' . basename(__FILE__) . '@';
    $paths = $splash->recursiveDirectory()->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // Feed Splash an array.
    $paths = Splash::go()->appendArray(array(
      __DIR__,
    ))->recursiveDirectory()->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);
  }

}