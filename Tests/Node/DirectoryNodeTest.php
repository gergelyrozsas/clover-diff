<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\DirectoryNode
 */
class DirectoryNodeTest extends TestCase {

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
   * @dataProvider dataProvider
   */
  public function testGetters(array $files, array $expected, array $expected_children): void {
    foreach ($files as $file) {
      $this->unit->addFile($file);
    }

    $this->assertEquals($expected['elements'], $this->unit->getElements());
    $this->assertEquals($expected['covered_elements'], $this->unit->getCoveredElements());

    foreach ($expected_children as $path_string => $child_expectations) {
      $path = $this->pathStringToPathArray($path_string);
      $child = $this->unit->getChild($path);

      if ($child_expectations['class']) {
        $this->assertInstanceOf($child_expectations['class'], $child);
        $this->assertEquals($child_expectations['elements'], $child->getElements());
        $this->assertEquals($child_expectations['covered_elements'], $child->getCoveredElements());
        continue;
      }

      $this->assertNull($child);
    }
  }

  public function dataProvider(): iterable {
    $cases = [
      'test case 1' => [
        'files' => [
          $this->createFileNode('dir_a/dir_b/file1', 10, 5),
          $this->createFileNode('dir_a/dir_b/file2', 10, 5),
          $this->createFileNode('dir_a/dir_b/dir_c/file3', 10, 5),
        ],
        'expected' => ['elements' => 30, 'covered_elements' => 15],
        'expected_children' => [
          '' => ['class' => NULL],
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

  private function createFileNode(string $path_string, int $elements, int $covered_elements) {
    $path = $this->pathStringToPathArray($path_string);

    /** @var \GergelyRozsas\CloverDiff\Node\FileNode|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(FileNode::class);
    $prophecy->getPath()->willReturn($path);
    $prophecy->getName()->willReturn(\end($path));
    $prophecy->getElements()->willReturn($elements);
    $prophecy->getCoveredElements()->willReturn($covered_elements);
    return $prophecy->reveal();
  }

  private function pathStringToPathArray(string $path): array {
    return !\strlen($path) ? [] : \explode('/', $path);
  }

}
