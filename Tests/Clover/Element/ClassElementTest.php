<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Element;

use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Clover\Element\CoverageElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Clover\Element\PackageElement;
use GergelyRozsas\CloverDiff\Clover\Element\ProjectElement;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\Element\ClassElement
 */
class ClassElementTest extends AbstractTest {

  use ForwardGetterTestTrait;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\ClassElement
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new ClassElement();
  }

  /**
   * {@inheritdoc}
   */
  public function forwardGetterDataProvider(): iterable {
    return [
      'getCoverage' => [
        'method_name' => 'getCoverage',
        'property_class' => FileElement::class,
        'property' => 'file',
        'value' => $this->prophesize(CoverageElement::class)->reveal(),
      ],
      'getProject' => [
        'method_name' => 'getProject',
        'property_class' => FileElement::class,
        'property' => 'file',
        'value' => $this->prophesize(ProjectElement::class)->reveal(),
      ],
      'getPackage' => [
        'method_name' => 'getPackage',
        'property_class' => FileElement::class,
        'property' => 'file',
        'value' => $this->prophesize(PackageElement::class)->reveal(),
      ],
    ];
  }

}
