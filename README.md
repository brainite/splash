Splash
======

[![Build Status](https://travis-ci.org/wittiws/splash.png?branch=master)](https://travis-ci.org/wittiws/splash)

Splash is a chainable (and therefore concise) way to interact with the SPL iterators.

```` php
    // Basic example.
    \Splash\Splash::mount();
    foreach (splash(__DIR__)->recursiveDirectory()->regex($match) as $path) {
    }
````

[For more information, visit the project page.](http://www.admin.witti.ws/project/splash)
