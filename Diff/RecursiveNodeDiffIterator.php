<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Diff;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;

class RecursiveNodeDiffIterator extends NodeDiffIterator implements \RecursiveIterator {

  /**
   * {@inheritdoc}
   */
  public function getChildren(): self {
    return new self(
      $this->getNewerChildren(),
      $this->getOlderChildren()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function hasChildren(): bool {
    $current = \current($this->newerNodes);
    return (
      ($current instanceof DirectoryNode) &&
      ($current->hasChildren())
    );
  }

  private function getNewerChildren(): array {
    $new_child = &$this->newerNodes[$this->key()];
    return ($new_child instanceof DirectoryNode) ? $new_child->getChildren() : [];
  }

  private function getOlderChildren(): array {
    $old_child = &$this->olderNodes[$this->key()];
    return ($old_child instanceof DirectoryNode) ? $old_child->getChildren() : [];
  }

}
