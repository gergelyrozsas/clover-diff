<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Diff;

/**
 * @codeCoverageIgnore
 */
class NodeDiffIterator implements \Iterator {

  /**
   * @var array
   */
  protected $newerNodes = [];

  /**
   * @var array
   */
  protected $olderNodes = [];

  public function __construct(array $newer_nodes, array $older_nodes) {
    $this->newerNodes = $newer_nodes;
    $this->olderNodes = $older_nodes;
  }

  /**
   * {@inheritdoc}
   */
  public function rewind(): void  {
    \reset($this->newerNodes);
  }

  /**
   * {@inheritdoc}
   */
  public function valid(): bool {
    return !\is_null(\key($this->newerNodes));
  }

  /**
   * {@inheritdoc}
   */
  public function key(): string {
    return \key($this->newerNodes);
  }

  /**
   * {@inheritdoc}
   */
  public function current(): ?NodeDiff {
    return $this->valid() ? new NodeDiff(
      $this->newerNodes[$this->key()],
      $this->olderNodes[$this->key()] ?? NULL
    ) : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function next(): void {
    \next($this->newerNodes);
  }

}
