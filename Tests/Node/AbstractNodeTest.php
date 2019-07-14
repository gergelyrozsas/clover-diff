<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node;

use GergelyRozsas\CloverDiff\Node\AbstractNode;
use GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\AbstractNode
 */
class AbstractNodeTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\AbstractNode
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = $this->getMockForAbstractClass(AbstractNode::class);
  }

  /**
   * @dataProvider pathDataProvider
   */
  public function testGetName(array $path, string $expected): void {
    $this->setObjectProperty($this->unit, 'path', $path);
    $this->assertEquals($expected, $this->unit->getName());
  }

  public function pathDataProvider(): iterable {
    return [
      [[], ''],
      [['non', 'empty', 'array'], 'array']
    ];
  }

  /**
   * @dataProvider revisionProxyGetterDataProvider
   */
  public function testRevisionProxyGetters(string $method_name, $value): void {
    $unit = $this->getMockBuilder(AbstractNode::class)
      ->setMethods(['getRevision'])
      ->getMockForAbstractClass();
    $revision_prophecy = $this->prophesize(NodeRevisionInterface::class);
    $revision_prophecy->{$method_name}()->willReturn($value);
    $unit->method('getRevision')
      ->with(-1)
      ->willReturn($revision_prophecy->reveal());
    $this->assertEquals($value, $unit->{$method_name}());
  }

  public function revisionProxyGetterDataProvider(): iterable {
    return [
      ['getTimestamp', 1000],
      ['getElements', 100],
      ['getCoveredElements', 10],
    ];
  }

  /**
   * @dataProvider getRevisionDataProvider
   */
  public function testGetRevision(array $revisions, int $revision_id, $expected): void {
    $this->setObjectProperty($this->unit, 'revisions', $revisions);

    if (is_subclass_of($expected, \Exception::class)) {
      $this->expectException($expected);
      $this->unit->getRevision($revision_id);
      return;
    }

    $this->assertSame($expected, $this->unit->getRevision($revision_id));
  }

  public function getRevisionDataProvider(): iterable {
    return [
      'non-negative revision id' => [
        [
          $revision_0 = $this->prophesize(NodeRevisionInterface::class)->reveal(),
          $this->prophesize(NodeRevisionInterface::class)->reveal(),
        ],
        0,
        $revision_0
      ],
      'negative revision id' => [
        [
          $this->prophesize(NodeRevisionInterface::class)->reveal(),
          $revision_1 = $this->prophesize(NodeRevisionInterface::class)->reveal(),
        ],
        -1,
        $revision_1
      ],
      'invalid revision id' => [
        [
          $this->prophesize(NodeRevisionInterface::class)->reveal(),
          $this->prophesize(NodeRevisionInterface::class)->reveal(),
        ],
        1000,
        \OutOfBoundsException::class
      ],
    ];
  }

}
