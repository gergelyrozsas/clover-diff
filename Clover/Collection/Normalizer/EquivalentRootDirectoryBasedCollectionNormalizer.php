<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;

class EquivalentRootDirectoryBasedCollectionNormalizer implements CollectionNormalizerInterface {

  /**
   * {@inheritdoc}
   */
  public function supports(CloverCollection $clovers): bool {
    $root_directories = $clovers->getRootDirectories();
    $unique_root_directories = \array_unique($root_directories);
    return (1 === \count($unique_root_directories));
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function normalize(CloverCollection $clovers) {
    // At this point there is nothing to be done,
    // as clovers were generated from the very same directory.
  }

}
