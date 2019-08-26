<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Utility;

class IterableUtil {

  public static function iterableToTraversable(iterable $iterable): \Traversable {
    yield from $iterable;
  }

  public static function iterableToArray(iterable $iterable): array {
    return \is_array($iterable) ? $iterable : \iterator_to_array($iterable);
  }

}
