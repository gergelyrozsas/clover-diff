<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;

class DelegatingCollectionNormalizer implements CollectionNormalizerInterface {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolverInterface
   */
  private $resolver;

  public function __construct(
    CollectionNormalizerResolverInterface $resolver
  ) {
    $this->resolver = $resolver;
  }

  /**
   * {@inheritdoc}
   */
  public function supports(CloverCollection $clovers): bool {
    return (bool) $this->resolver->resolve($clovers);
  }

  /**
   * {@inheritdoc}
   */
  public function normalize(CloverCollection $clovers) {
    if ($normalizer = $this->resolver->resolve($clovers)) {
      $normalizer->normalize($clovers);
    }
  }

}
