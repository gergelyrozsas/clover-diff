<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

abstract class AbstractCollectionNormalizerTestCase extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface
   */
  protected $unit;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\CloverCollection|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $collection;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->collection = $this->prophesize(CloverCollection::class);
  }

}
