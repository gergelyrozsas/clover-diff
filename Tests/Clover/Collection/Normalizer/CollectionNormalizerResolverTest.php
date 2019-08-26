<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolver;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @coversDefaultClass \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolver
 */
class CollectionNormalizerResolverTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolver
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new CollectionNormalizerResolver();
  }

  /**
   * @dataProvider resolveDataProvider
   */
  public function testResolve(
    CloverCollection $collection,
    CollectionNormalizerInterface $normalizer1,
    CollectionNormalizerInterface $normalizer2,
    ?CollectionNormalizerInterface $expected
  ): void {
    $this->setObjectProperty($this->unit, 'normalizers', [
      200 => [$normalizer1],
      100 => [$normalizer2],
    ]);
    if ($expected) {
      $this->assertSame($expected, $this->unit->resolve($collection));
    }
    else {
      $this->assertNull($this->unit->resolve($collection));
    }
  }

  public function resolveDataProvider(): iterable {
    /** @var \GergelyRozsas\CloverDiff\Clover\CloverCollection $collection */
    $collection = $this->prophesize(CloverCollection::class)->reveal();
    return [
      'no normalizers support the collection' => [
        'collection' => $collection,
        'normalizer1' => $this->createNormalizer($collection, FALSE),
        'normalizer2' => $this->createNormalizer($collection, FALSE),
        'expected' => NULL,
      ],
      'only normalizer2 supports the collection' => [
        'collection' => $collection,
        'normalizer1' => $this->createNormalizer($collection, FALSE),
        'normalizer2' => $normalizer2 = $this->createNormalizer($collection, TRUE),
        'expected' => $normalizer2,
      ],
      'both normalizers supports the collection' => [
        'collection' => $collection,
        'normalizer1' => $normalizer1 = $this->createNormalizer($collection, TRUE),
        'normalizer2' => $this->createNormalizer($collection, TRUE),
        'expected' => $normalizer1,
      ],
    ];
  }

  public function testAddNormalizer(): void {
    /** @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface $normalizer */
    $normalizer = $this->prophesize(CollectionNormalizerInterface::class)->reveal();
    $expected_normalizers = [
      100 => [$normalizer],
    ];
    $this->setObjectProperty($this->unit, 'sorted', ['some', 'array']);

    $this->unit->addNormalizer($normalizer, 100);
    $this->assertNull($this->getObjectProperty($this->unit, 'sorted'));
    $this->assertEquals($expected_normalizers, $this->getObjectProperty($this->unit, 'normalizers'));
  }

  private function createNormalizer(CloverCollection $collection, bool $supports): CollectionNormalizerInterface {
    /** @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(CollectionNormalizerInterface::class);
    $prophecy->supports($collection)->willReturn($supports);
    return $prophecy->reveal();
  }

}
