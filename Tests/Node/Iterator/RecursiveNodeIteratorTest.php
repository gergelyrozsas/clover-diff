<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node\Iterator;

use GergelyRozsas\CloverDiff\Node\Iterator\RecursiveNodeIterator;
use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\Iterator\RecursiveNodeIterator
 */
class RecursiveNodeIteratorTest extends AbstractTest {

  /**
   * @dataProvider currentDataProvider
   */
  public function testRecursivity(
    NodeInterface $current,
    ?RecursiveNodeIterator $child_iterator
  ): void {
    /** @var \GergelyRozsas\CloverDiff\Node\Iterator\RecursiveNodeIterator|\PHPUnit\Framework\MockObject\MockObject $unit */
    $unit = $this->getMockBuilder(RecursiveNodeIterator::class)
      ->disableOriginalConstructor()
      ->setMethods(['current'])
      ->getMock();
    $unit->method('current')->willReturn($current);

    $this->assertEquals((bool) $child_iterator, $unit->hasChildren());
    if ($child_iterator) {
      $this->assertEquals($child_iterator, $unit->getChildren());
    }
  }

  public function currentDataProvider(): iterable {
    return [
      'node without children' => [
        'node' => $this->createNode(NULL),
        'child_iterator' => NULL,
      ],
      'node with children iterator' => [
        'node' => $node = $this->createNode([1, 2, 3, 4]),
        'child_iterator' => new RecursiveNodeIterator($node),
      ],
    ];
  }

  private function createNode($children = NULL): NodeInterface {
    /** @var \GergelyRozsas\CloverDiff\Node\NodeInterface|\Prophecy\Prophecy\ObjectProphecy $node */
    $node = $this->prophesize(NodeInterface::class);
    $node->hasChildren()->willReturn((bool) $children);
    $node->getChildren()->willReturn($children);
    return $node->reveal();
  }

}
