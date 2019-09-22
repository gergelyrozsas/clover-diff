<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;

class CollectionNormalizerResolver implements CollectionNormalizerResolverInterface {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface[][]
   */
  private $normalizers = [];

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface[]
   */
  private $sorted;

  public function __construct(array $normalizers = []) {
    $this->normalizers = $normalizers;
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(CloverCollection $clovers): ?CollectionNormalizerInterface {
    $this->sortNormalizers();
    foreach ($this->sorted as $normalizer) {
      if ($normalizer->supports($clovers)) {
        return $normalizer;
      }
    }

    return NULL;
  }

  public function addNormalizer(CollectionNormalizerInterface $normalizer, int $priority): void {
    $this->normalizers[$priority][] = $normalizer;
    $this->sorted = NULL;
  }

  private function sortNormalizers(): void {
    if (isset($this->sorted)) {
      // @codeCoverageIgnoreStart
      return;
      // @codeCoverageIgnoreEnd
    }

    $this->sorted = [];
    \krsort($this->normalizers);
    foreach ($this->normalizers as $priority => $normalizers) {
      foreach ($normalizers as $normalizer) {
        $this->sorted[] = $normalizer;
      }
    }
  }

}
