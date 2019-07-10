<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Revision;

abstract class AbstractNodeRevision implements NodeRevisionInterface {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\NodeInterface
   */
  protected $node;

  /**
   * @var int|null
   */
  protected $elements;

  /**
   * @var int|null
   */
  protected $coveredElements;

  /**
   * @var int
   */
  protected $revisionId;

  /**
   * @var int
   */
  protected $timestamp;

  /**
   * {@inheritdoc}
   */
  public function getPath(): array {
    return $this->node->getPath();
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->node->getName();
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function getTimestamp(): int {
    return $this->timestamp;
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function getElements(): ?int {
    return $this->elements;
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function getCoveredElements(): ?int {
    return $this->coveredElements;
  }

  /**
   * {@inheritdoc}
   */
  public function getChildren(): ?array {
    return $this->node->getChildren();
  }

  /**
   * {@inheritdoc}
   */
  public function hasChildren(): bool {
    return $this->node->hasChildren();
  }

  /**
   * {@inheritdoc}
   */
  public function getRevision(int $revision_id): NodeRevisionInterface {
    return $this->node->getRevision($revision_id);
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisions(): array {
    return $this->node->getRevisions();
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function getRevisionId(): int {
    return $this->revisionId;
  }

}
