[![Build Status](https://travis-ci.org/gergelyrozsas/clover-diff.svg?branch=master)](https://travis-ci.org/gergelyrozsas/clover-diff)

# GergelyRozsas\CloverDiff

**GergelyRozsas\CloverDiff** is a library that provides processing and rendering functionality for comparing code coverage information from Clover XML files.

## Installation

This library can be installed via [Composer](https://getcomposer.org/):

    composer require gergelyrozsas/clover-diff

## Using the GergelyRozsas\CloverDiff API

```php
<?php

use GergelyRozsas\CloverDiff\CloverDiff;

$diff = new CloverDiff();
$report = $diff->compare('/path/to/clover1.xml', '/path/to/clover2.xml');

foreach ($report as $node_diff) {
  if ($node_diff->isDirectoryNode()) {
    // ...
  }
  else {
    // ...
  }
}
```

A build in HTML report generator can also be used if the symfony/filesystem library is installed.

```php
<?php

use GergelyRozsas\CloverDiff\CloverDiff;
use GergelyRozsas\CloverDiff\Report;

$diff = new CloverDiff();
$report = $diff->compare('/path/to/clover1.xml', '/path/to/clover2.xml');

$generator = new Report\Html();
$options = $generator->process($report);

echo "The report was generated into the '{$options['target']}' directory.";
```

## Credits

The concept for the HTML report was adopted from https://github.com/sebastianbergmann/php-code-coverage.
