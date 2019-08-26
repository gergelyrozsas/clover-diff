<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;

interface CollectionNormalizerInterface {

  /**
   * Checks whether the normalizer supports the given Clovers.
   *
   * @param \GergelyRozsas\CloverDiff\Clover\CloverCollection $clovers
   *   The Clovers.
   *
   * @return bool
   *   TRUE if the normalizer supports the Clovers, FALSE otherwise.
   */
  public function supports(CloverCollection $clovers): bool;

  /**
   * Normalizes the Clovers so they can be compared later on.
   *
   * Normalization mainly focuses on adjusting file names within the Clovers of the collection in a way,
   * that they all appear to be from the same directory.
   *
   * @param \GergelyRozsas\CloverDiff\Clover\CloverCollection $clovers
   *   The clovers.
   */
  public function normalize(CloverCollection $clovers);

}
