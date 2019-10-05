<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Element;

use GergelyRozsas\CloverDiff\Clover\Element\CoverageElement;
use GergelyRozsas\CloverDiff\Clover\Element\ProjectElement;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\Element\CoverageElement
 */
class CoverageElementTest extends AbstractTest {

  use ForwardGetterTestTrait;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\CoverageElement
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new CoverageElement();
  }

  public function testSetProject(): void {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\ProjectElement|\Prophecy\Prophecy\ObjectProphecy $project */
    $project = $this->prophesize(ProjectElement::class);
    $project->setCoverage($this->unit)->shouldBeCalledTimes(1);
    $this->unit->setProject($project->reveal());
  }

  /**
   * {@inheritdoc}
   */
  public function forwardGetterDataProvider(): iterable {
    // Any class that is "iterable" should be equivalent. Unfortunately \Traversable cannot be used.
    $iterator = $this->prophesize(\Iterator::class)->reveal();
    return [
      'getPackages' => [
        'method_name' => 'getPackages',
        'property_class' => ProjectElement::class,
        'property' => 'project',
        'value' => $iterator,
      ],
      'getFiles' => [
        'method_name' => 'getFiles',
        'property_class' => ProjectElement::class,
        'property' => 'project',
        'value' => $iterator,
      ],
      'getClasses' => [
        'method_name' => 'getClasses',
        'property_class' => ProjectElement::class,
        'property' => 'project',
        'value' => $iterator,
      ],
    ];
  }

}
