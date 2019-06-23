<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Diff;

use GergelyRozsas\CloverDiff\Diff\NodeDiff;
use GergelyRozsas\CloverDiff\Diff\NodeDiffIterator;
use GergelyRozsas\CloverDiff\Node\AbstractNode;
use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GergelyRozsas\CloverDiff\Diff\NodeDiff
 */
class NodeDiffTest extends TestCase {

  /**
   * @dataProvider unitDataProvider
   */
  public function testUnit(AbstractNode $newer, ?AbstractNode $older, array $expected) {
    $unit = new NodeDiff($newer, $older);

    $this->assertEquals($expected['path'], $unit->getPath());
    $this->assertEquals($expected['name'], $unit->getName());
    $this->assertEquals($expected['is_directory'], $unit->isDirectoryNode());
    $this->assertEquals($expected['old_elements'], $unit->getOldElements());
    $this->assertEquals($expected['old_covered_elements'], $unit->getOldCoveredElements());
    $this->assertEquals($expected['old_percentage'], $unit->getOldPercentage());
    $this->assertEquals($expected['new_elements'], $unit->getNewElements());
    $this->assertEquals($expected['new_covered_elements'], $unit->getNewCoveredElements());
    $this->assertEquals($expected['new_percentage'], $unit->getNewPercentage());
    $this->assertEquals($expected['percentage_diff'], $unit->getPercentageDiff());
    $this->assertEquals($expected['children'], $unit->getChildren());
  }

  public function unitDataProvider(): iterable {
    $cases = [
      'case: newer file node, no older node' => [
        new FileNode($path = ['path1', 'to', 'file'], 78, 62),
        NULL,
        [
          'path' => $path,
          'name' => 'file',
          'is_directory' => FALSE,
          'old_elements' => NULL,
          'old_covered_elements' => NULL,
          'old_percentage' => NULL,
          'new_elements' => 78,
          'new_covered_elements' => 62,
          'new_percentage' => 100 * 62 / 78,
          'percentage_diff' => 100 * 62 / 78,
          'children' => new NodeDiffIterator([], []),
        ],
      ],
      'case: newer file node without elements, no older node' => [
        new FileNode($path = ['path1', 'to', 'file'], 0, 0),
        NULL,
        [
          'path' => $path,
          'name' => 'file',
          'is_directory' => FALSE,
          'old_elements' => NULL,
          'old_covered_elements' => NULL,
          'old_percentage' => NULL,
          'new_elements' => 0,
          'new_covered_elements' => 0,
          'new_percentage' => NULL,
          'percentage_diff' => NULL,
          'children' => new NodeDiffIterator([], []),
        ],
      ],
      'case: newer file node, older file node' => [
        new FileNode($path = ['path2', 'to', 'file'], 10, 6),
        new FileNode($path, 10, 3),
        [
          'path' => $path,
          'name' => 'file',
          'is_directory' => FALSE,
          'old_elements' => 10,
          'old_covered_elements' => 3,
          'old_percentage' => 30,
          'new_elements' => 10,
          'new_covered_elements' => 6,
          'new_percentage' => 60,
          'percentage_diff' => 30,
          'children' => new NodeDiffIterator([], []),
        ],
      ],
      'case: newer directory node, older directory node' => [
        $this->createDirectoryNode($path = ['path', 'to', 'dir'], 40, 16, $new_children = [
          'file1' => new FileNode(array_merge($path, ['file1']), 32, 8),
          'file2' => new FileNode(array_merge($path, ['file2']), 8, 8),
        ]),
        $this->createDirectoryNode($path, 32, 8, $old_children = [
          'file1' => new FileNode(array_merge($path, ['file1']), 140, 32),
        ]),
        [
          'path' => $path,
          'name' => 'dir',
          'is_directory' => TRUE,
          'old_elements' => 32,
          'old_covered_elements' => 8,
          'old_percentage' => 25,
          'new_elements' => 40,
          'new_covered_elements' => 16,
          'new_percentage' => 40,
          'percentage_diff' => 15,
          'children' => new NodeDiffIterator($new_children, $old_children),
        ],
      ],
    ];
    return $cases;
  }

  private function createDirectoryNode(array $path, int $elements, int $covered_elements, array $children): DirectoryNode {
    /** @var \GergelyRozsas\CloverDiff\Node\DirectoryNode|\Prophecy\Prophecy\ObjectProphecy $directory_node */
    $directory_node = $this->prophesize(DirectoryNode::class);
    $directory_node->getPath()
      ->willReturn($path);
    $directory_node->getName()
      ->willReturn(end($path));
    $directory_node->getElements()
      ->willReturn($elements);
    $directory_node->getCoveredElements()
      ->willReturn($covered_elements);
    $directory_node->getChildren()
      ->willReturn($children);
    return $directory_node->reveal();
  }

}

