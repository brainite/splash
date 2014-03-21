<?php
namespace Splash\Tests;

use Splash\Splash;
class BasicTest extends \PHPUnit_Framework_TestCase {
  public function testMount() {
    // Confirm that multiple calls are safe.
    Splash::mount();
    Splash::mount();
    Splash::mount();

    // Perform a basic test of the splash function.
    $expected = "1 2 3";
    $actual = '';
    foreach (splash(1, 2, 3) as $k) {
      $actual .= ' ' . $k;
    }
    $actual = trim($actual);
    $this->assertEquals($expected, $actual);
  }

  /**
   * @depends testMount
   */
  public function testFilesystem() {
    $match = '@' . basename(__FILE__) . '@';

    // The iterators should easily locate this file.
    $splash = Splash::go()->append(__DIR__);
    $paths = $splash->recursiveDirectory()->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // Shorthand.
    $matches = 0;
    foreach (splash(__DIR__)->recursiveDirectory()->regex($match) as $path) {
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

    // Feed Splash a RecursiveDirectoryIterator.
    $paths = Splash::go()->appendRecursiveDirectory(__DIR__)->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // Feed Splash a RecursiveDirectoryIterator.
    $paths = Splash::go()->appendDirectory(__DIR__)->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path->getPathname()));
    }
    $this->assertEquals(1, $matches);
  }

  public function testUnique() {
    // The iterators should easily locate this file.
    $splash = Splash::go()->append(__DIR__, __DIR__);
    $match = '@' . basename(__FILE__) . '@';
    $paths = $splash->recursiveDirectory()->unique()->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // The iterators should easily locate this file.
    $splash = Splash::go()->appendArray(array(
      __DIR__,
      __DIR__,
    ));
    $match = '@' . basename(__FILE__) . '@';
    $paths = $splash->recursiveDirectory()->unique()->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);
  }

}