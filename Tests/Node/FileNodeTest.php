<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node;

use GergelyRozsas\CloverDiff\Node\FileNode;
use GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\FileNode
 */
class FileNodeTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\FileNode
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new FileNode(['path', 'to', 'file']);
  }

  public function testConstructor() {
    $this->assertEquals(['path', 'to', 'file'], $this->unit->getPath());
  }

  public function testGetChildren(): void {
    $this->assertNull($this->unit->getChildren());
  }

  public function testHasChildren(): void {
    $this->assertFalse($this->unit->hasChildren());
  }

  public function testAddRevision(): void {
    $expected = [
      1 => new FileNodeRevision($this->unit, 1, 1000, 14, 78)
    ];
    $this->unit->addRevision(1, 1000, 14, 78);
    $this->assertEquals($expected, $this->unit->getRevisions());
  }

}
