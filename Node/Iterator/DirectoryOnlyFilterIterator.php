<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Iterator;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;

class DirectoryOnlyFilterIterator extends \FilterIterator implements \RecursiveIterator {

  /**
   * @var \Iterator|\RecursiveIterator
   */
  private $iterator;

  /**
   * @var bool
   */
  private $isRecursive;

  /**
   * {@inheritdoc}
   */
  public function __construct(\Iterator $iterator) {
    $this->iterator = $iterator;
    $this->isRecursive = $iterator instanceof \RecursiveIterator;
    parent::__construct($iterator);
  }

  /**
   * {@inheritdoc}
   */
  public function accept(): bool {
    $node = $this->current();
    return $node instanceof DirectoryNode;
  }

  /**
   * {@inheritdoc}
   */
  public function getChildren(): \RecursiveIterator {
    return new self($this->iterator->getChildren());
  }

  /**
   * {@inheritdoc}
   */
  public function hasChildren(): bool {
    return $this->isRecursive && $this->iterator->hasChildren();
  }

}
