<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Report\Html\Utility;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;
use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Report\Html\Utility\NodeSort;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Report\Html\Utility\NodeSort
 */
class NodeSortTest extends AbstractTest {

  /**
   * @dataProvider sortByTypeDataProvider
   */
  public function testSortByType(array $nodes, array $expected): void {
    uasort($nodes, NodeSort::sortByType());
    $this->assertEquals(serialize($nodes), serialize($expected));
  }

  public function sortByTypeDataProvider(): iterable {
    return [
      'only files' => [
        'nodes' => [
          'key1' => $node1 = $this->createFileNode('b'),
          'key2' => $node2 = $this->createFileNode('a'),
          'key3' => $node3 = $this->createFileNode('c'),
          'key4' => $node4 = $this->createFileNode('d'),
        ],
        'expected' => ['key2' => $node2, 'key1' => $node1, 'key3' => $node3, 'key4' => $node4],
      ],
      'two directories, two files' => [
        'nodes' => [
          'key1' => $node1 = $this->createFileNode('n'),
          'key2' => $node2 = $this->createDirectoryNode('m'),
          'key3' => $node3 = $this->createDirectoryNode('k'),
          'key4' => $node4 = $this->createFileNode('l'),
        ],
        'expected' => ['key3' => $node3, 'key2' => $node2, 'key4' => $node4, 'key1' => $node1],
      ],
      'only directories' => [
        'nodes' => [
          'key1' => $node1 = $this->createDirectoryNode('y'),
          'key2' => $node2 = $this->createDirectoryNode('w'),
          'key3' => $node3 = $this->createDirectoryNode('z'),
          'key4' => $node4 = $this->createDirectoryNode('x'),
        ],
        'expected' => ['key2' => $node2, 'key4' => $node4, 'key1' => $node1, 'key3' => $node3],
      ],
    ];
  }

  private function createDirectoryNode(string $name): DirectoryNode {
    return $this->createNode(DirectoryNode::class, $name);
  }

  private function createFileNode(string $name): FileNode {
    return $this->createNode(FileNode::class, $name);
  }

  private function createNode(string $class, string $name): NodeInterface {
    /** @var \GergelyRozsas\CloverDiff\Node\NodeInterface|\Prophecy\Prophecy\ObjectProphecy $node */
    $node = $this->prophesize($class);
    $node->getName()->willReturn($name);
    return $node->reveal();
  }

}
