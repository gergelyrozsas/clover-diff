<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Report\Html\Utility;

use GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface;
use GergelyRozsas\CloverDiff\Report\Html\Utility\NodeMath;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Report\Html\Utility\NodeMath
 */
class NodeMathTest extends AbstractTest {

  /**
   * @dataProvider getPercentageDataProvider
   */
  public function testGetPercentage(NodeRevisionInterface $revision, ?float $expected): void {
    $this->assertEquals($expected, NodeMath::getPercentage($revision));
  }

  public function getPercentageDataProvider(): iterable {
    return [
      'no data available' => [
        'revision' => $this->createNodeRevision(NULL, NULL),
        'expected' => NULL,
      ],
      'no elements data is available' => [
        'revision' => $this->createNodeRevision(10000, NULL),
        'expected' => NULL,
      ],
      'elements is zero' => [
        'revision' => $this->createNodeRevision(10000, 0),
        'expected' => NULL,
      ],
      'all data is available' => [
        'revision' => $this->createNodeRevision(78, 100),
        'expected' => 78,
      ],
    ];
  }

  /**
   * @dataProvider getPercentageDiffDataProvider
   */
  public function testGetPercentageDiff(
    ?int $percentage1,
    ?int $percentage2,
    ?float $expected
  ): void {
    $revision1 = $this->createNodeRevision($percentage1, 100);
    $revision2 = $this->createNodeRevision($percentage2, 100);
    $this->assertEquals($expected, NodeMath::getPercentageDiff($revision1, $revision2));
  }

  public function getPercentageDiffDataProvider(): iterable {
    return [
      'both percentages are NULL' => [
        'percentage1' => NULL,
        'percentage2' => NULL,
        'expected' => NULL,
      ],
      'percentage2 is NULL'  => [
        'percentage1' => 34,
        'percentage2' => NULL,
        'expected' => 34,
      ],
      'both percentages are non-null' => [
        'percentage1' => 79,
        'percentage2' => 35,
        'expected' => 44,
      ],
    ];
  }

  private function createNodeRevision(?int $covered_elements, ?int $elements): NodeRevisionInterface {
    /** @var \GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface|\Prophecy\Prophecy\ObjectProphecy $revision */
    $revision = $this->prophesize(NodeRevisionInterface::class);
    $revision->getCoveredElements()->willReturn($covered_elements);
    $revision->getElements()->willReturn($elements);
    return $revision->reveal();
  }

}
