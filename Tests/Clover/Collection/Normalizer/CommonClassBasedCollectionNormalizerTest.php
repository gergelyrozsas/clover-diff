<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CommonClassBasedCollectionNormalizer;
use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use Prophecy\Argument;

/**
 * @coversDefaultClass \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CommonClassBasedCollectionNormalizer
 */
class CommonClassBasedCollectionNormalizerTest extends AbstractCollectionNormalizerTestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->unit = new CommonClassBasedCollectionNormalizer();
  }

  /**
   * @dataProvider supportsDataProvider
   */
  public function testSupports(array $common_classes, bool $expected): void {
    $this->collection->map(Argument::any())->willReturn($common_classes);
    $this->assertEquals($expected, $this->unit->supports($this->collection->reveal()));
  }

  public function supportsDataProvider(): iterable {
    return [
      'no common classes' => [
        'common_classes' => [
          0 => ['\Namespace0\Class0' => $this->createClass('\Namespace0\Class0')],
          1 => ['\Namespace1\Class1' => $this->createClass('\Namespace1\Class1')],
          2 => ['\Namespace2\Class2' => $this->createClass('\Namespace2\Class2')],
        ],
        'expected' => FALSE,
      ],
      'have common classes' => [
        'common_classes' => [
          0 => ['\Namespace\Class' => $this->createClass('\Namespace\Class')],
          1 => ['\Namespace\Class' => $this->createClass('\Namespace\Class')],
          2 => ['\Namespace\Class' => $this->createClass('\Namespace\Class')],
        ],
        'expected' => TRUE,
      ],
    ];
  }

  private function createClass(string $class_name): ClassElement {
    /** @var \GergelyRozsas\CloverDiff\Clover\Element\ClassElement|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(ClassElement::class);
    $prophecy->getName()->willReturn($class_name);
    return $prophecy->reveal();
  }

}
