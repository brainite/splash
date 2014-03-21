splash
======

[![Build Status](https://travis-ci.org/wittiws/splash.png?branch=master)](https://travis-ci.org/wittiws/splash)

SPLash is a chainable way to interact with the SPL iterators.

```` php
    foreach (splash(__DIR__)->recursiveDirectory()->regex($match) as $path) {
    }
````