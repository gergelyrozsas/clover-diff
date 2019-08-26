<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Element;

use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Clover\Element\CoverageElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Clover\Element\PackageElement;
use GergelyRozsas\CloverDiff\Clover\Element\ProjectElement;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;
use Prophecy\Argument;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\Element\FileElement
 */
class FileElementTest extends AbstractTest {

  use ForwardGetterTestTrait;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\FileElement
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new FileElement();
  }

  /**
   * {@inheritdoc}
   */
  public function forwardGetterDataProvider(): iterable {
    return [
      'getCoverage' => [
        'method_name' => 'getCoverage',
        'property_class' => PackageElement::class,
        'property' => 'package',
        'value' => $this->prophesize(CoverageElement::class)->reveal(),
      ],
      'getProject' => [
        'method_name' => 'getProject',
        'property_class' => PackageElement::class,
        'property' => 'package',
        'value' => $this->prophesize(ProjectElement::class)->reveal(),
      ],
    ];
  }

  public function testGetClasses(): void {
    $class1 = $this->createClassElement('class1');
    $class2 = $this->createClassElement('class2');
    $expected = [
      'class1' => $class1,
      'class2' => $class2,
    ];
    $this->setObjectProperty($this->unit, 'classes', \array_values($expected));
    $this->assertEquals($expected, \iterator_to_array($this->unit->getClasses()));
  }

  /**
   * @dataProvider classesDataProvider
   */
  public function testAddClasses(array $class_elements, int $count): void {
    foreach ($class_elements as $class_element) {
      $this->unit->addClass($class_element);
    }
    $this->assertCount($count, $this->getObjectProperty($this->unit, 'classes'));
  }

  public function classesDataProvider(): iterable {
    return [
      'same object twice' => [
        'class_elements' => [
          $class = $this->createClassElement(NULL, 1),
          $class,
        ],
        'count' => 1,
      ],
      'different objects' => [
        'class_elements' => [
          $this->createClassElement(NULL, 1),
          $this->createClassElement(NULL, 1),
        ],
        'count' => 2,
      ]
    ];
  }

  private function createClassElement(string $name = NULL, int $calls = 0): ClassElement {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\ClassElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(ClassElement::class);
    if (isset($name)) {
      $prophecy->getName()->willReturn($name);
    }
    if (0 < $calls) {
      $prophecy->setFile(Argument::any())->shouldBeCalledTimes($calls);
    }
    return $prophecy->reveal();
  }

}
