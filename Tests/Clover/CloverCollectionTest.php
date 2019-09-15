<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover;

use GergelyRozsas\CloverDiff\Clover\Clover;
use GergelyRozsas\CloverDiff\Clover\CloverCollection;
use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Clover\Element\PackageElement;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\CloverCollection
 */
class CloverCollectionTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\CloverCollection
   */
  private $unit;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Clover|\Prophecy\Prophecy\ObjectProphecy
   */
  private $clover1;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Clover|\Prophecy\Prophecy\ObjectProphecy
   */
  private $clover2;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Clover|\Prophecy\Prophecy\ObjectProphecy
   */
  private $clover3;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->clover1 = $this->createClover(0, 100);
    $this->clover2 = $this->createClover(1, 200);
    $this->clover3 = $this->createClover(2, 300);
    $this->unit = new CloverCollection([
      $this->clover1->reveal(),
      $this->clover2->reveal(),
      $this->clover3->reveal(),
    ]);
  }

  /**
   * @dataProvider getCloverByRevisionDataProvider
   */
  public function testGetCloverByRevision(int $revision_id, string $expected) {
    if ('exception' === $expected) {
      $this->expectException(\OutOfBoundsException::class);
      $this->unit->getCloverByRevision($revision_id);
    }
    else {
      $this->assertSame($this->{$expected}->reveal(), $this->unit->getCloverByRevision($revision_id));
    }
  }

  public function getCloverByRevisionDataProvider(): iterable {
    return [
      'invalid revision id' => [
        'revision_id' => PHP_INT_MAX,
        'expected' => 'exception',
      ],
      'non-negative revision id' => [
        'revision_id' => 1,
        'expected' => 'clover2',
      ],
      'negative revision id' => [
        'revision_id' => -1,
        'expected' => 'clover3',
      ],
    ];
  }

  /**
   * @dataProvider getCloverByTimestampDataProvider
   */
  public function testGetCloverByTimestamp(int $timestamp, string $expected) {
    if ('exception' === $expected) {
      $this->expectException(\OutOfBoundsException::class);
      $this->unit->getCloverByTimestamp($timestamp);
    }
    else {
      $this->assertSame($this->{$expected}->reveal(), $this->unit->getCloverByTimestamp($timestamp));
    }
  }

  public function getCloverByTimestampDataProvider(): iterable {
    return [
      'invalid timestamp' => [
        'timestamp' => -1,
        'expected' => 'exception',
      ],
      'valid timestamp' => [
        'revision_id' => 100,
        'expected' => 'clover1',
      ],
    ];
  }

  public function testGetLatest(): void {
    $this->assertSame($this->clover3->reveal(), $this->unit->getLatest());
  }

  public function testGetRootDirectories(): void {
    $this->clover1->getFiles()->willReturn([
      $this->createFile('/dir1/file1'),
      $this->createFile('/dir1/file2'),
    ]);
    $this->clover2->getFiles()->willReturn([
      $this->createFile('C:\\path\\to\\project\\file1'),
      $this->createFile('C:\\path\\to\\project\\subdir\\file2'),
    ]);
    $this->clover3->getFiles()->willReturn([
      $this->createFile('/no/common'),
      $this->createFile('/directory/here'),
    ]);

    $expected = [
      0 => '/dir1/',
      1 => 'C:\\path\\to\\project\\',
      2 => '/',
    ];
    $this->assertEquals($expected, $this->unit->getRootDirectories());
  }

  /**
   * @dataProvider getElementsDataProvider
   */
  public function testGetElements(
    string $method,
    iterable $clover1_data,
    iterable $clover2_data,
    iterable $clover3_data,
    array $expected
  ): void {
    $this->clover1->{$method}()->willReturn($clover1_data);
    $this->clover2->{$method}()->willReturn($clover2_data);
    $this->clover3->{$method}()->willReturn($clover3_data);
    $this->assertEquals($expected, iterator_to_array($this->unit->{$method}()));
  }

  public function getElementsDataProvider(): iterable {
    return [
      'getPackages' => [
        'method' => 'getPackages',
        'clover1_data' => ['package1' => $package1 = $this->createElement(PackageElement::class, 'package1')],
        'clover2_data' => ['package2' => $package2 = $this->createElement(PackageElement::class, 'package2')],
        'clover3_data' => ['package3' => $package3 = $this->createElement(PackageElement::class, 'package3')],
        'expected' => [
          'package1' => $package1,
          'package2' => $package2,
          'package3' => $package3,
        ]
      ],
      'getFiles' => [
        'method' => 'getFiles',
        'clover1_data' => ['file1' => $file1 = $this->createElement(FileElement::class, 'file1')],
        'clover2_data' => ['file2' => $file2 = $this->createElement(FileElement::class, 'file2')],
        'clover3_data' => ['file3' => $file3 = $this->createElement(FileElement::class, 'file3')],
        'expected' => [
          'file1' => $file1,
          'file2' => $file2,
          'file3' => $file3,
        ]
      ],
      'getClasses' => [
        'method' => 'getClasses',
        'clover1_data' => ['class1' => $class1 = $this->createElement(ClassElement::class, 'class1')],
        'clover2_data' => ['class2' => $class2 = $this->createElement(ClassElement::class, 'class2')],
        'clover3_data' => ['class3' => $class3 = $this->createElement(ClassElement::class, 'class3')],
        'expected' => [
          'class1' => $class1,
          'class2' => $class2,
          'class3' => $class3,
        ]
      ],
    ];
  }

  public function testGetIterator(): void {
    $expected = new \ArrayIterator([
      $this->clover1->reveal(),
      $this->clover2->reveal(),
      $this->clover3->reveal(),
    ]);
    $this->assertEquals($expected, $this->unit->getIterator());
  }

  private function createClover(int $revision_id, int $timestamp) {
    /** @var \GergelyRozsas\CloverDiff\Clover\Clover|\Prophecy\Prophecy\ObjectProphecy $prophecy*/
    $prophecy = $this->prophesize(Clover::class);
    $prophecy->getRevisionId()->willReturn($revision_id);
    $prophecy->getTimestamp()->willReturn($timestamp);
    return $prophecy;
  }

  private function createFile(string $name): FileElement {
    return $this->createElement(FileElement::class, $name);
  }

  private function createElement(string $class, string $name) {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\FileElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize($class);
    $prophecy->getName()->willReturn($name);
    return $prophecy->reveal();
  }

}
