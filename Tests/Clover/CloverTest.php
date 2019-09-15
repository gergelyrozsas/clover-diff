<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover;

use GergelyRozsas\CloverDiff\Clover\Clover;
use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Clover\Element\CoverageElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Clover\Element\PackageElement;
use GergelyRozsas\CloverDiff\Clover\Element\ProjectElement;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\Clover
 */
class CloverTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Clover
   */
  private $unit;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\CoverageElement|\Prophecy\Prophecy\ObjectProphecy
   */
  private $coverage;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->coverage = $this->prophesize(CoverageElement::class);
    $this->unit = new Clover($this->coverage->reveal(), 1);
  }

  public function testGetTimestamp(): void {
    $this->coverage->getGenerated()->willReturn(1234);
    $this->assertEquals(1234, $this->unit->getTimestamp());
  }

  public function testGetProject(): void {
    $project = $this->prophesize(ProjectElement::class)->reveal();
    $this->coverage->getProject()->willReturn($project);
    $this->assertSame($project, $this->unit->getProject());
  }

  /**
   * @dataProvider getElementsDataProvider
   */
  public function testGetElements(string $method): void {
    $iterator = $this->prophesize(\Iterator::class)->reveal();
    $this->coverage->{$method}()->willReturn($iterator);
    $this->assertSame($iterator, $this->unit->{$method}());
  }

  public function getElementsDataProvider(): iterable {
    return [
      'getPackages' => [
        'method' => 'getPackages',
      ],
      'getFiles' => [
        'method' => 'getFiles',
      ],
      'getClasses' => [
        'method' => 'getClasses',
      ],
    ];
  }

  /**
   * @dataProvider getElementDataProvider
   */
  public function testGetElement(string $element_class, string $forward_method_name, string $unit_method_name): void {
    $element = $this->prophesize($element_class)->reveal();
    $this->coverage->{$forward_method_name}()->willReturn([
      'valid-key' => $element,
    ]);
    $this->assertNull($this->unit->{$unit_method_name}('any string other than "valid-key"'));
    $this->assertSame($element, $this->unit->{$unit_method_name}('valid-key'));
  }

  public function getElementDataProvider(): iterable {
    return [
      'getPackage' => [
        'element_class' => PackageElement::class,
        'forward_method_name' => 'getPackages',
        'unit_method_name' => 'getPackage',
      ],
      'getFile' => [
        'element_class' => FileElement::class,
        'forward_method_name' => 'getFiles',
        'unit_method_name' => 'getFile',
      ],
      'getClass' => [
        'element_class' => ClassElement::class,
        'forward_method_name' => 'getClasses',
        'unit_method_name' => 'getClass',
      ],
    ];
  }

}
