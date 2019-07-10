<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Iterator;

class SortableIterator implements \IteratorAggregate {

  /**
   * @var \Traversable
   */
  private $iterator;

  /**
   * @var callable
   */
  private $callback;

  public function __construct(\Traversable $iterator, callable $uasort_callback) {
    $this->iterator = $iterator;
    $this->callback = $uasort_callback;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    $array = iterator_to_array($this->iterator, true);
    uasort($array, $this->callback);
    return new \ArrayIterator($array);
  }

}
