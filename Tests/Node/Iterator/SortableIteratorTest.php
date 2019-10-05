<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node\Iterator;

use GergelyRozsas\CloverDiff\Node\Iterator\SortableIterator;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\Iterator\SortableIterator
 */
class SortableIteratorTest extends AbstractTest {

  /**
   * @dataProvider constructorDataProvider
   */
  public function testGetIterator(
    \Traversable $iterator,
    callable $uasort_callback,
    array $children
  ): void {
    $unit = new SortableIterator($iterator, $uasort_callback);
    $this->assertEquals(serialize($children), serialize(iterator_to_array($unit)));
  }

  public function constructorDataProvider(): iterable {
    return [
      'empty traversable' => [
        'node' => $this->createTraversable([]),
        'uasort_callback' => function(){ throw new \LogicException('This should not be thrown, as the node has no children.'); },
        'children' => [],
      ],
      'non-empty traversable' => [
        'node' => $this->createTraversable(['a' => 4, 'b' => 2, 'c' => 1, 'd' => 3]),
        'uasort_callback' => function($a, $b) { return $a - $b; },
        'children' => ['c' => 1, 'b' => 2, 'd' => 3, 'a' => 4],
      ],
    ];
  }

  private function createTraversable(array $children): \IteratorAggregate {
    /** @var \IteratorAggregate|\Prophecy\Prophecy\ObjectProphecy $iterator */
    $iterator = $this->prophesize(\IteratorAggregate::class);
    $iterator->getIterator()->willReturn(new \ArrayIterator($children));
    return $iterator->reveal();
  }

}
