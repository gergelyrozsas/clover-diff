<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;

interface CollectionNormalizerResolverInterface {

  /**
   * Returns a normalizer instance able to normalize the Clovers.
   *
   * @param \GergelyRozsas\CloverDiff\Clover\CloverCollection $clovers
   *   The clovers.
   *
   * @return \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface|null
   *   A normalizer if one found, NULL otherwise.
   */
  public function resolve(CloverCollection $clovers): ?CollectionNormalizerInterface;

}
