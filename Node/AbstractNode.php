<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node;

use GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface;

abstract class AbstractNode implements NodeInterface {

  /**
   * @var string[]
   */
  protected $path = [];

  /**
   * @var \GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface[]
   */
  protected $revisions = [];

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function getPath(): array {
    return $this->path;
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return (string) \end($this->path);
  }

  /**
   * {@inheritdoc}
   */
  public function getTimestamp(): int {
    return $this->getLatestRevision()->getTimestamp();
  }

  /**
   * {@inheritdoc}
   */
  public function getElements(): ?int {
    return $this->getLatestRevision()->getElements();
  }

  /**
   * {@inheritdoc}
   */
  public function getCoveredElements(): ?int {
    return $this->getLatestRevision()->getCoveredElements();
  }

  /**
   * {@inheritdoc}
   */
  public function getRevision(int $revision_id): NodeRevisionInterface {
    if ($revision_id < 0) {
      // Jump to the end of the revisions array.
      $revision_id += \count($this->revisions);
    }

    if (isset($this->revisions[$revision_id])) {
      return $this->revisions[$revision_id];
    }

    throw new \OutOfBoundsException();
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function getRevisions(): array {
    return $this->revisions;
  }

  private function getLatestRevision(): NodeRevisionInterface {
    return $this->getRevision(-1);
  }

}
