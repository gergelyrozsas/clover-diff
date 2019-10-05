<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node\Iterator;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;
use GergelyRozsas\CloverDiff\Node\Iterator\DirectoryOnlyFilterIterator;
use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\Iterator\DirectoryOnlyFilterIterator
 */
class DirectoryOnlyFilterIteratorTest extends AbstractTest {

  /**
   * @dataProvider currentDataProvider
   */
  public function testAccept(NodeInterface $current, bool $accepted): void {
    /** @var \GergelyRozsas\CloverDiff\Node\Iterator\DirectoryOnlyFilterIterator|\PHPUnit\Framework\MockObject\MockObject $unit */
    $unit = $this->getMockBuilder(DirectoryOnlyFilterIterator::class)
      ->disableOriginalConstructor()
      ->setMethods(['current'])
      ->getMock();
    $unit->method('current')->willReturn($current);
    $this->assertEquals($accepted, $unit->accept());
  }

  public function currentDataProvider(): iterable {
    return [
      'accepted' => [
        $this->prophesize(DirectoryNode::class)->reveal(),
        TRUE,
      ],
      'not accepted' => [
        $this->prophesize(FileNode::class)->reveal(),
        FALSE,
      ]
    ];
  }

  /**
   * @dataProvider iteratorDataProvider
   */
  public function testRecursivity(\Iterator $iterator, ?\RecursiveIterator $child_iterator): void {
    $unit = new DirectoryOnlyFilterIterator($iterator);
    $this->assertEquals((bool) $child_iterator, $unit->hasChildren());
    if ($child_iterator) {
      $this->assertEquals($child_iterator, $unit->getChildren());
    }
  }

  public function iteratorDataProvider(): iterable {
    return [
      'non-recursive iterator' => [
        'iterator' => $this->prophesize(\Iterator::class)->reveal(),
        'child_iterator' => NULL,
      ],
      'recursive iterator' => [
        'iterator' => $this->createRecursiveIterator(
          $children = $this->createRecursiveIterator()
        ),
        'child_iterator' => new DirectoryOnlyFilterIterator($children),
      ],
    ];
  }

  private function createRecursiveIterator(?\RecursiveIterator $children = NULL): \RecursiveIterator {
    /** @var \RecursiveIterator|\Prophecy\Prophecy\ObjectProphecy $recursive_iterator */
    $recursive_iterator = $this->prophesize(\RecursiveIterator::class);
    $recursive_iterator->hasChildren()->willReturn((bool) $children);
    if ($children) {
      $recursive_iterator->getChildren()->willReturn($children);
    }
    return $recursive_iterator->reveal();
  }

}
