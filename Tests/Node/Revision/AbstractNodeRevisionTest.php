<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Node\Revision;

use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Node\Revision\AbstractNodeRevision;
use GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Node\Revision\AbstractNodeRevision
 */
class AbstractNodeRevisionTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\Revision\AbstractNodeRevision
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = $this->getMockForAbstractClass(AbstractNodeRevision::class);
  }

  /**
   * @dataProvider nodeProxyGetterDataProvider
   */
  public function testNodeProxyGetters(string $method, array $args, $value): void {
    /** @var \GergelyRozsas\CloverDiff\Node\NodeInterface|\Prophecy\Prophecy\ObjectProphecy $node */
    $node = $this->prophesize(NodeInterface::class);
    $node->{$method}(...$args)->willReturn($value);
    $this->setObjectProperty($this->unit, 'node', $node->reveal());
    $this->assertEquals($value, $this->unit->{$method}(...$args));
  }

  public function nodeProxyGetterDataProvider(): iterable {
    return [
      ['getPath', [], ['path']],
      ['getName', [], 'name'],
      ['getChildren', [], ['children1', 'children2']],
      ['hasChildren', [], TRUE],
      ['getRevision', [1], $this->prophesize(NodeRevisionInterface::class)->reveal()],
      ['getRevisions', [], ['revision1', 'revision2']],
    ];
  }

}
