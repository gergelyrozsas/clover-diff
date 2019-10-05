<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\EquivalentRootDirectoryBasedCollectionNormalizer;

/**
 * @coversDefaultClass \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\EquivalentRootDirectoryBasedCollectionNormalizer
 */
class EquivalentRootDirectoryBasedCollectionNormalizerTest extends AbstractCollectionNormalizerTestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->unit = new EquivalentRootDirectoryBasedCollectionNormalizer();
  }

  /**
   * @dataProvider supportsDataProvider
   */
  public function testSupports(array $root_directories, bool $expected): void {
    $this->collection->getRootDirectories()->willReturn($root_directories);
    $this->assertEquals($expected, $this->unit->supports($this->collection->reveal()));
  }

  public function supportsDataProvider(): iterable {
    return [
      'completely different root directories' => [
        'root_directories' => [
          '/var/www/project/root/',
          'C:\\htdocs\\project\\root',
        ],
        'expected' => FALSE,
      ],
      'somewhat different root directories' => [
        'root_directories' => [
          '/path/to/project/root/',
          '/path/to/project/',
        ],
        'expected' => FALSE,
      ],
      'equivalent root directories' => [
        'root_directories' => [
          '/var/www/project/root/',
          '/var/www/project/root/',
        ],
        'expected' => TRUE,
      ],
    ];
  }

}
