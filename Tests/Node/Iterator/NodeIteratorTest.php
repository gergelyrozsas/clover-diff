<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node\Iterator;

use GergelyRozsas\CloverDiff\Node\Iterator\NodeIterator;
use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\Iterator\NodeIterator
 */
class NodeIteratorTest extends AbstractTest {

  /**
   * @dataProvider constructorDataProvider
   */
  public function testConstructor(
    NodeInterface $node,
    array $children
  ): void {
    $unit = new NodeIterator($node);
    $this->assertEquals($children, $unit->getArrayCopy());
  }

  public function constructorDataProvider(): iterable {
    return [
      'node without children' => [
        'node' => $this->createNode(NULL),
        'children' => [],
      ],
      'node with children' => [
        'node' => $node = $this->createNode(['a' => 4, 'b' => 2, 'c' => 1, 'd' => 3]),
        'children' => ['a' => 4, 'b' => 2, 'c' => 1, 'd' => 3],
      ],
    ];
  }

  private function createNode(?array $children = NULL): NodeInterface {
    /** @var \GergelyRozsas\CloverDiff\Node\NodeInterface|\Prophecy\Prophecy\ObjectProphecy $node */
    $node = $this->prophesize(NodeInterface::class);
    $node->hasChildren()->willReturn((bool) $children);
    $node->getChildren()->willReturn($children);
    return $node->reveal();
  }

}
