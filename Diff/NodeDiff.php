<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Diff;

use GergelyRozsas\CloverDiff\Node\AbstractNode;
use GergelyRozsas\CloverDiff\Node\DirectoryNode;

class NodeDiff {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\AbstractNode
   */
  private $newer;

  /**
   * @var \GergelyRozsas\CloverDiff\Node\AbstractNode|null
   */
  private $older;

  public function __construct(AbstractNode $newer, ?AbstractNode $older) {
    $this->newer = $newer;
    $this->older = $older;
  }

  public function getPath(): array {
    return $this->newer->getPath();
  }

  public function getName(): string {
    return $this->newer->getName();
  }

  public function isDirectoryNode(): bool {
    return ($this->newer instanceof DirectoryNode);
  }

  public function getOldElements(): ?int {
    return $this->older ? $this->older->getElements() : NULL;
  }

  public function getOldCoveredElements(): ?int {
    return $this->older ? $this->older->getCoveredElements() : NULL;
  }

  public function getOldPercentage(): ?float {
    return $this->getPercentage($this->getOldCoveredElements(), $this->getOldElements());
  }

  public function getNewElements(): int {
    return $this->newer->getElements();
  }

  public function getNewCoveredElements(): int {
    return $this->newer->getCoveredElements();
  }

  public function getNewPercentage(): ?float {
    return $this->getPercentage($this->getNewCoveredElements(), $this->getNewElements());
  }

  public function getPercentageDiff(): ?float {
    if (\is_null($this->getNewPercentage())) {
      return NULL;
    }

    if (\is_null($this->getOldPercentage())) {
      return $this->getNewPercentage();
    }

    return $this->getNewPercentage() - $this->getOldPercentage();
  }

  public function getChildren(): NodeDiffIterator {
    return new NodeDiffIterator(
      $this->getNodeChildren($this->newer),
      $this->getNodeChildren($this->older)
    );
  }

  private function getPercentage(?int $a, ?int $b): ?float {
    if (\is_null($a) || !$b) {
      return NULL;
    }
    return 100 * $a / $b;
  }

  private function getNodeChildren(?AbstractNode $node) {
    if (!($node instanceof DirectoryNode)) {
      return [];
    }

    return $node->getChildren();
  }

}
