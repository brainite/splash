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

    // Test the count function.
    $this->assertEquals(0, splash()->count(), "Test count method in empty case.");
    for ($i = 1; $i <= 10; $i++) {
      $this->assertEquals($i, splash()->appendArray(array_fill(0, $i, 'X'))->count(), "Test count method in basic case.");
    }
    $this->assertEquals(3, splash(1)->push(1)->push(1)->count(), "Test count method with multiple appends.");
  }

  /**
   * @depends testMount
   */
  public function testFilesystem() {
    $match = '@(?:^|/)' . basename(__FILE__) . '$@';
    $flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO;

    // The iterators should easily locate this file.
    clearstatcache(TRUE);
    $splash = Splash::go()->push(__DIR__);
    $this->assertEquals(1, $splash->count(), "Pushing first item should make count = 1.");
    $allpaths = $splash->recursiveDirectory($flags);
    foreach ($allpaths as $p) {
      echo "all: $p\n";
    }
    $this->assertGreaterThanOrEqual(2, $allpaths->count(), "All paths together should be at least 2");
    $paths = $allpaths->regex($match);
    foreach ($paths as $p) {
      echo "regex: $p\n";
    }
    $this->assertEquals(1, $paths->count(), "There should only be one regex match.");
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // Repeat the first test.
    clearstatcache(TRUE);
    $splash = Splash::go()->push(__DIR__);
    $this->assertEquals(1, $splash->count(), "Pushing first item should make count = 1.");
    $paths = $splash->recursiveDirectory()->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // Shorthand.
    $matches = 0;
    clearstatcache(TRUE);
    $paths = splash(__DIR__)->recursiveDirectory($flags)->regex($match);
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // Feed Splash an array.
    clearstatcache(TRUE);
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
    clearstatcache(TRUE);
    $paths = Splash::go()->appendRecursiveDirectory(__DIR__)->regex($match);
    $matches = 0;
    foreach ($paths as $path) {
      ++$matches;
      $this->assertEquals(realpath(__FILE__), realpath($path));
    }
    $this->assertEquals(1, $matches);

    // Feed Splash a RecursiveDirectoryIterator.
    clearstatcache(TRUE);
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
    $splash = Splash::go()->push(__DIR__, __DIR__);
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