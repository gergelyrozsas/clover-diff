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
 * @covers \GergelyRozsas\CloverDiff\Clover\Element\PackageElement
 */
class PackageElementTest extends AbstractTest {

  use ForwardGetterTestTrait;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\PackageElement
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new PackageElement();
  }

  /**
   * {@inheritdoc}
   */
  public function forwardGetterDataProvider(): iterable {
    return [
      'getCoverage' => [
        'method_name' => 'getCoverage',
        'property_class' => ProjectElement::class,
        'property' => 'project',
        'value' => $this->prophesize(CoverageElement::class)->reveal(),
      ],
    ];
  }

  public function testGetFiles(): void {
    $file1 = $this->createFileElement(['name' => 'file1']);
    $file2 = $this->createFileElement(['name' => 'file2']);
    $expected = [
      'file1' => $file1,
      'file2' => $file2,
    ];
    $this->setObjectProperty($this->unit, 'files', \array_values($expected));
    $this->assertEquals($expected, \iterator_to_array($this->unit->getFiles()));
  }

  /**
   * @dataProvider filesDataProvider
   */
  public function testAddFiles(array $elements, int $count): void {
    foreach ($elements as $element) {
      $this->unit->addFile($element);
    }
    $this->assertCount($count, $this->getObjectProperty($this->unit, 'files'));
  }

  public function filesDataProvider(): iterable {
    return [
      'same object twice' => [
        'elements' => [
          $file = $this->createFileElement(['calls' => 1]),
          $file,
        ],
        'count' => 1,
      ],
      'different objects' => [
        'elements' => [
          $this->createFileElement(['calls' => 1]),
          $this->createFileElement(['calls' => 1]),
        ],
        'count' => 2,
      ]
    ];
  }

  public function testGetClasses(): void {
    $class1 = $this->createClassElement();
    $file1 = $this->createFileElement(['classes' => [
      'class1' => $class1,
    ]]);
    $class2 = $this->createClassElement();
    $file2 = $this->createFileElement(['classes' => [
      'class2' => $class2,
    ]]);
    $expected = [
      'class1' => $class1,
      'class2' => $class2,
    ];
    $this->setObjectProperty($this->unit, 'files', [$file1, $file2]);
    $this->assertEquals($expected, \iterator_to_array($this->unit->getClasses()));
  }

  private function createFileElement(array $options = []): FileElement {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\FileElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(FileElement::class);
    if (isset($options['name'])) {
      $prophecy->getName()->willReturn($options['name']);
    }
    if (0 < ($options['calls'] ?? 0)) {
      $prophecy->setPackage(Argument::any())->shouldBeCalledTimes($options['calls']);
    }
    if (isset($options['classes'])) {
      $prophecy->getClasses()->willReturn($options['classes']);
    }
    return $prophecy->reveal();
  }

  private function createClassElement(): ClassElement {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\ClassElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(ClassElement::class);
    return $prophecy->reveal();
  }

}
