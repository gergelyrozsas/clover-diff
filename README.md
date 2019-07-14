[![Latest Stable Version](https://poser.pugx.org/gergelyrozsas/clover-diff/v/stable)](https://packagist.org/packages/gergelyrozsas/clover-diff)
[![Build Status](https://travis-ci.org/gergelyrozsas/clover-diff.svg?branch=master)](https://travis-ci.org/gergelyrozsas/clover-diff)
[![Code Coverage](https://codecov.io/gh/gergelyrozsas/clover-diff/branch/master/graphs/badge.svg?branch=master)](https://codecov.io/github/gergelyrozsas/clover-diff?branch=master)

# GergelyRozsas\CloverDiff

**GergelyRozsas\CloverDiff** is a library that provides processing and rendering functionality for comparing code coverage information from Clover XML files.

## Installation

This library can be installed via [Composer](https://getcomposer.org/):

    composer require gergelyrozsas/clover-diff

## Using the GergelyRozsas\CloverDiff API

```php
<?php

use GergelyRozsas\CloverDiff\CloverDiff;
use GergelyRozsas\CloverDiff\Node\Iterator\RecursiveNodeIterator;

$diff = new CloverDiff();
$report = $diff->compare([
  '/path/to/clover1.xml',
  '/path/to/clover2.xml',
]);

$iterator = new \RecursiveIteratorIterator(
  new RecursiveNodeIterator($report),
  \RecursiveIteratorIterator::SELF_FIRST
); 

/** @var \GergelyRozsas\CloverDiff\Node\NodeInterface $node */
foreach ($iterator as $node) {
  foreach ($node->getRevisions() as $revision) {
    echo \vsprintf("Coverage for %s on %s was %.2f%%.\n", [
      \implode('/', $node->getPath()),
      \date('Y-m-d H:i:s', $revision->getTimestamp()),
      \round(100 * $revision->getCoveredElements() / $revision->getElements()),
    ]);
  }
}
```

A build in HTML report generator can also be used if the symfony/filesystem library is installed.

```php
<?php

use GergelyRozsas\CloverDiff\CloverDiff;
use GergelyRozsas\CloverDiff\Report;

$diff = new CloverDiff();
$report = $diff->compare([
  '/path/to/clover1.xml',
  '/path/to/clover2.xml',
]);

$generator = new Report\Html();
$options = $generator->process($report);

echo "The report was generated into the '{$options['target']}' directory.";
```

## Credits

The concept for the HTML report was adopted from https://github.com/sebastianbergmann/php-code-coverage.
