<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;
use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\DirectoryNode
 */
class DirectoryNodeTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\DirectoryNode
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new DirectoryNode();
  }

  /**
   * @dataProvider structureDataProvider
   */
  public function testStructure(array $files, array $expected): void {
    foreach ($files as $file) {
      $this->unit->addFile($file);
    }

    foreach ($expected as $path_string => $expectations) {
      $path = $this->pathStringToPathArray($path_string);
      $child = $this->getChild($path);

      if ($expectations['class']) {
        $this->assertInstanceOf($expectations['class'], $child);
        $this->assertEquals($expectations['elements'], $child->getElements());
        $this->assertEquals($expectations['covered_elements'], $child->getCoveredElements());
        continue;
      }

      $this->assertNull($child);
    }
  }

  public function structureDataProvider(): iterable {
    $cases = [
      'test case 1' => [
        'files' => [
          $this->createFileNode('dir_a/dir_b/file1', 10, 5),
          $this->createFileNode('dir_a/dir_b/file2', 10, 5),
          $this->createFileNode('dir_a/dir_b/dir_c/file3', 10, 5),
        ],
        'expected' => [
          '' => ['class' => DirectoryNode::class, 'elements' => 30, 'covered_elements' => 15],
          'this_does_not_exist' => ['class' => NULL],
          'dir_a' => ['class' => DirectoryNode::class, 'elements' => 30, 'covered_elements' => 15],
          'dir_a/dir_b' => ['class' => DirectoryNode::class, 'elements' => 30, 'covered_elements' => 15],
          'dir_a/dir_b/file1' => ['class' => FileNode::class, 'elements' => 10, 'covered_elements' => 5],
          'dir_a/dir_b/file2' => ['class' => FileNode::class, 'elements' => 10, 'covered_elements' => 5],
          'dir_a/dir_b/file1/anything' => ['class' => NULL],
          'dir_a/dir_b/dir_c' => ['class' => DirectoryNode::class, 'elements' => 10, 'covered_elements' => 5],
          'dir_a/dir_b/dir_c/file3' => ['class' => FileNode::class, 'elements' => 10, 'covered_elements' => 5],
        ],
      ],
    ];

    foreach ($cases as $case) {
      yield \array_values($case);
    }
  }

  private function createFileNode(string $path_string, int $elements, int $covered_elements): FileNode {
    $path = $this->pathStringToPathArray($path_string);

    /** @var \GergelyRozsas\CloverDiff\Node\FileNode|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(FileNode::class);
    $prophecy->getPath()->willReturn($path);
    $prophecy->getName()->willReturn(\end($path));
    $prophecy->getElements()->willReturn($elements);
    $prophecy->getCoveredElements()->willReturn($covered_elements);
    $prophecy->getChildren()->willReturn(NULL);
    $prophecy->getRevisions()->willReturn([
      $this->createFileNodeRevision(0, 1000, $elements, $covered_elements),
    ]);
    return $prophecy->reveal();
  }

  private function pathStringToPathArray(string $path): array {
    return !\strlen($path) ? [] : \explode('/', $path);
  }

  private function createFileNodeRevision(
    int $revision_id,
    int $timestamp,
    ?int $elements,
    ?int $covered_elements
  ): FileNodeRevision {
    /** @var \GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(FileNodeRevision::class);
    $prophecy->getRevisionId()->willReturn($revision_id);
    $prophecy->getTimestamp()->willReturn($timestamp);
    $prophecy->getElements()->willReturn($elements);
    $prophecy->getCoveredElements()->willReturn($covered_elements);
    return $prophecy->reveal();
  }

  private function getChild(array $path_elements): ?NodeInterface {
    $node = $this->unit;
    foreach ($path_elements as $path_element) {
      $children = $node->getChildren() ?? [];
      if (!isset($children[$path_element])) {
        return NULL;
      }
      $node = $children[$path_element];
    }
    return $node;
  }

}
