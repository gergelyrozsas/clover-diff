<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Iterator;

class RecursiveNodeIterator extends NodeIterator implements \RecursiveIterator {

  /**
   * {@inheritdoc}
   */
  public function getChildren(): \RecursiveIterator {
    return new self($this->current());
  }

  /**
   * {@inheritdoc}
   */
  public function hasChildren(): bool {
    return $this->current()->hasChildren();
  }

}
