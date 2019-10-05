<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node\Revision;

use GergelyRozsas\CloverDiff\Node\Revision\DirectoryNodeRevision;
use GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\Revision\DirectoryNodeRevision
 */
class DirectoryNodeRevisionTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\Revision\DirectoryNodeRevision
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = $this->getMockBuilder(DirectoryNodeRevision::class)
      ->disableOriginalConstructor()
      ->setMethods(NULL)
      ->getMock();
  }

  /**
   * @dataProvider fileNodeRevisionDataProvider
   */
  public function testAddFileNodeRevision(
    FileNodeRevision $file_node_revision,
    ?int $expected_elements,
    ?int $expected_covered_elements
  ): void {
    $this->unit->addFileNodeRevision($file_node_revision);
    $this->assertEquals($expected_elements, $this->unit->getElements());
    $this->assertEquals($expected_covered_elements, $this->unit->getCoveredElements());
  }

  public function fileNodeRevisionDataProvider(): iterable {
    return [
      [$this->createFileNodeRevision(NULL, NULL), NULL, NULL],
      [$this->createFileNodeRevision(5, 3), 5, 3],
    ];
  }

  private function createFileNodeRevision(?int $elements, ?int $covered_elements): FileNodeRevision {
    /** @var \GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision|\Prophecy\Prophecy\ObjectProphecy $file_node_revision */
    $file_node_revision = $this->prophesize(FileNodeRevision::class);
    $file_node_revision->getElements()->willReturn($elements);
    $file_node_revision->getCoveredElements()->willReturn($covered_elements);
    return $file_node_revision->reveal();
  }

}
