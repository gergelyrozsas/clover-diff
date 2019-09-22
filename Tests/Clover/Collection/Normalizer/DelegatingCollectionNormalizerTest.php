<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolverInterface;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\DelegatingCollectionNormalizer;
use Prophecy\Argument;

/**
 * @coversDefaultClass \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\DelegatingCollectionNormalizer
 */
class DelegatingCollectionNormalizerTest extends AbstractCollectionNormalizerTestCase {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolverInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  private $resolver;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->collection = $this->collection->reveal();
    $this->resolver = $this->prophesize(CollectionNormalizerResolverInterface::class);
    $this->unit = new DelegatingCollectionNormalizer(
      $this->resolver->reveal()
    );
  }

  /**
   * @dataProvider supportsDataProvider
   */
  public function testSupports(?CollectionNormalizerInterface $resolved, bool $expected): void {
    $this->resolver->resolve($this->collection)->willReturn($resolved);
    $this->assertEquals($expected, $this->unit->supports($this->collection));
  }

  public function supportsDataProvider(): iterable {
    return [
      'no suitable normalizer exists' => [
        'resolved' => NULL,
        'expected' => FALSE,
      ],
      'suitable normalizer exists' => [
        'resolved' => $this->createNormalizer(),
        'expected' => TRUE,
      ],
    ];
  }

  /**
   * @dataProvider normalizeDataProvider
   */
  public function testNormalize(?CollectionNormalizerInterface $resolved): void {
    $this->resolver->resolve($this->collection)
      ->willReturn($resolved)
      ->shouldBeCalled();
    $this->unit->normalize($this->collection);
  }

  public function normalizeDataProvider(): iterable {
    return [
      'no suitable normalizer exists' => [
        'resolved' => NULL,
      ],
      'suitable normalizer exists' => [
        'resolved' => $this->createNormalizer(1),
      ],
    ];
  }

  private function createNormalizer(?int $calls = NULL): CollectionNormalizerInterface {
    /** @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface|\Prophecy\Prophecy\ObjectProphecy $prophecy */
    $prophecy = $this->prophesize(CollectionNormalizerInterface::class);
    if (isset($calls)) {
      $prophecy->normalize(Argument::any())->shouldBeCalledTimes($calls);
    }
    return $prophecy->reveal();
  }

}
