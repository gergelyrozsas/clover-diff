<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Diff;

use GergelyRozsas\CloverDiff\Diff\RecursiveNodeDiffIterator;
use GergelyRozsas\CloverDiff\Node\AbstractNode;
use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GergelyRozsas\CloverDiff\Diff\RecursiveNodeDiffIterator
 */
class RecursiveNodeDiffIteratorTest extends TestCase {

  /**
   * @dataProvider unitDataProvider
   */
  public function testUnit(AbstractNode $newer, ?AbstractNode $older, ?RecursiveNodeDiffIterator $expected): void {
    $unit = new RecursiveNodeDiffIterator(['root' => $newer], $older ? ['root' => $older] : []);
    $this->assertEquals((bool) $expected, $unit->hasChildren());
    if ($expected) {
      $this->assertEquals($expected, $unit->getChildren());
    }
  }

  public function unitDataProvider(): iterable {
    $cases = [
      'case: newer file node, no older node' => [
        $this->createFileNode(),
        NULL,
        NULL,
      ],
      'case: newer file node, older file node' => [
        $this->createFileNode(),
        $this->createFileNode(),
        NULL,
      ],
      'case: newer file node, older directory node' => [
        $this->createFileNode(),
        $this->createDirectoryNode([
          $this->createFileNode(),
          $this->createDirectoryNode([
            $this->createFileNode(),
          ]),
        ]),
        NULL,
      ],
      'case: newer empty directory node, no older node' => [
        $this->createDirectoryNode(),
        NULL,
        NULL,
      ],
      'case: newer empty directory node, older file node' => [
        $this->createDirectoryNode(),
        $this->createFileNode(),
        NULL,
      ],
      'case: newer empty directory node, older directory node' => [
        $this->createDirectoryNode(),
        $this->createDirectoryNode([
          $this->createFileNode(),
          $this->createDirectoryNode([
            $this->createFileNode(),
          ]),
        ]),
        NULL,
      ],
      'case: newer non-empty directory node, no older node' => [
        $this->createDirectoryNode([
          'file' => $file = $this->createFileNode(),
          'dir' => $dir = $this->createDirectoryNode([
            'file2' => $this->createFileNode(),
          ]),
        ]),
        NULL,
        new RecursiveNodeDiffIterator(['file' => $file, 'dir' => $dir], []),
      ],
      'case: newer non-empty directory node, older file node' => [
        $this->createDirectoryNode([
          'file' => $file = $this->createFileNode(),
          'dir' => $dir = $this->createDirectoryNode([
            'file2' => $this->createFileNode(),
          ]),
        ]),
        $this->createFileNode(),
        new RecursiveNodeDiffIterator(['file' => $file, 'dir' => $dir], []),
      ],
      'case: newer non-empty directory node, older empty directory node' => [
        $this->createDirectoryNode([
          'file' => $file = $this->createFileNode(),
          'dir' => $dir = $this->createDirectoryNode([
            'file2' => $this->createFileNode(),
          ]),
        ]),
        $this->createDirectoryNode(),
        new RecursiveNodeDiffIterator(['file' => $file, 'dir' => $dir], []),
      ],
      'case: newer non-empty directory node, older non-empty directory node' => [
        $this->createDirectoryNode([
          'file' => $file = $this->createFileNode(),
          'dir' => $dir = $this->createDirectoryNode([
            'file2' => $this->createFileNode(),
          ]),
        ]),
        $this->createDirectoryNode([
          'old_file' => $old_file = $this->createFileNode(),
          'old_dir' => $old_dir = $this->createDirectoryNode([
            'old_sub_file' => $this->createFileNode(),
          ]),
        ]),
        new RecursiveNodeDiffIterator(['file' => $file, 'dir' => $dir], ['old_file' => $old_file, 'old_dir' => $old_dir]),
      ],
    ];
    return $cases;
  }

  private function createFileNode(): FileNode {
    /** @var \GergelyRozsas\CloverDiff\Node\FileNode|\Prophecy\Prophecy\ObjectProphecy $directory_node */
    $file_node = $this->prophesize(FileNode::class);
    return $file_node->reveal();
  }

  private function createDirectoryNode(array $children = []): DirectoryNode {
    /** @var \GergelyRozsas\CloverDiff\Node\DirectoryNode|\Prophecy\Prophecy\ObjectProphecy $directory_node */
    $directory_node = $this->prophesize(DirectoryNode::class);
    $directory_node->hasChildren()
      ->willReturn((bool) \count($children));
    $directory_node->getChildren()
      ->willReturn($children);
    return $directory_node->reveal();
  }

}

