<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Element;

use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Clover\Element\PackageElement;
use GergelyRozsas\CloverDiff\Clover\Element\ProjectElement;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;
use Prophecy\Argument;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\Element\ProjectElement
 */
class ProjectElementTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\ProjectElement
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new ProjectElement();
  }

  public function testGetPackages(): void {
    $package1 = $this->createPackageElement(['name' => 'package1']);
    $package2 = $this->createPackageElement(['name' => 'package2']);
    $expected = [
      'package1' => $package1,
      'package2' => $package2,
    ];
    $this->setObjectProperty($this->unit, 'packages', \array_values($expected));
    $this->assertEquals($expected, \iterator_to_array($this->unit->getPackages()));
  }

  /**
   * @dataProvider packagesDataProvider
   */
  public function testAddPackages(array $elements, int $count): void {
    foreach ($elements as $element) {
      $this->unit->addPackage($element);
    }
    $this->assertCount($count, $this->getObjectProperty($this->unit, 'packages'));
  }

  public function packagesDataProvider(): iterable {
    return [
      'same object twice' => [
        'elements' => [
          $package = $this->createPackageElement(['calls' => 1]),
          $package,
        ],
        'count' => 1,
      ],
      'different objects' => [
        'elements' => [
          $this->createPackageElement(['calls' => 1]),
          $this->createPackageElement(['calls' => 1]),
        ],
        'count' => 2,
      ]
    ];
  }

  public function testGetFiles(): void {
    $file1 = $this->createFileElement();
    $package1 = $this->createPackageElement(['files' => [
      'file1' => $file1,
    ]]);
    $file2 = $this->createFileElement();
    $package2 = $this->createPackageElement(['files' => [
      'file2' => $file2,
    ]]);
    $expected = [
      'file1' => $file1,
      'file2' => $file2,
    ];
    $this->setObjectProperty($this->unit, 'packages', [$package1, $package2]);
    $this->assertEquals($expected, \iterator_to_array($this->unit->getFiles()));
  }

  public function testGetClasses(): void {
    $class1 = $this->createClassElement();
    $package1 = $this->createPackageElement(['classes' => [
      'class1' => $class1,
    ]]);
    $class2 = $this->createClassElement();
    $package2 = $this->createPackageElement(['classes' => [
      'class2' => $class2,
    ]]);
    $expected = [
      'class1' => $class1,
      'class2' => $class2,
    ];
    $this->setObjectProperty($this->unit, 'packages', [$package1, $package2]);
    $this->assertEquals($expected, \iterator_to_array($this->unit->getClasses()));
  }

  private function createPackageElement(array $options = []): PackageElement {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\PackageElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(PackageElement::class);
    if (isset($options['name'])) {
      $prophecy->getName()->willReturn($options['name']);
    }
    if (0 < ($options['calls'] ?? 0)) {
      $prophecy->setProject(Argument::any())->shouldBeCalledTimes($options['calls']);
    }
    if (isset($options['files'])) {
      $prophecy->getFiles()->willReturn($options['files']);
    }
    if (isset($options['classes'])) {
      $prophecy->getClasses()->willReturn($options['classes']);
    }
    return $prophecy->reveal();
  }

  private function createFileElement(): FileElement {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\FileElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(FileElement::class);
    return $prophecy->reveal();
  }

  private function createClassElement(): ClassElement {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\ClassElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(ClassElement::class);
    return $prophecy->reveal();
  }

}
