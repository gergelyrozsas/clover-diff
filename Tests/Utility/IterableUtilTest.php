<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Utility;

use GergelyRozsas\CloverDiff\Tests\AbstractTest;
use GergelyRozsas\CloverDiff\Utility\IterableUtil;

/**
 * @coversDefaultClass \GergelyRozsas\CloverDiff\Utility\IterableUtil
 */
class IterableUtilTest extends AbstractTest {

  /**
   * @covers ::iterableToTraversable
   *
   * @dataProvider iterableDataProvider
   */
  public function testIterableToTraversable(iterable $iterable, array $expected) {
    $actual = IterableUtil::iterableToTraversable($iterable);
    $this->assertEquals($expected, \iterator_to_array($actual));
  }

  public function iterableDataProvider(): iterable {
    return [
      'iterable is an array' => [
        'iterable' => ['a' => 1, 'b' => 2, 'c' => 3],
        'expected' => ['a' => 1, 'b' => 2, 'c' => 3],
      ],
      'iterable is a \Traversable' => [
        'iterable' => new \ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
        'expected' => ['a' => 1, 'b' => 2, 'c' => 3],
      ],
    ];
  }

  /**
   * @covers ::iterableToArray
   *
   * @dataProvider iterableDataProvider
   */
  public function testIterableToArray(iterable $iterable, array $expected) {
    $this->assertEquals($expected, IterableUtil::iterableToArray($iterable));
  }

}
