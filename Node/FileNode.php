<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node;

use GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision;

class FileNode extends AbstractNode {

  public function __construct(array $path_elements) {
    $this->path = $path_elements;
  }

  /**
   * {@inheritdoc}
   */
  public function getChildren(): ?array {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function hasChildren(): bool {
    return FALSE;
  }

  /**
   * Adds a file node revision.
   *
   * @param int $revision_id
   *   The id of the revision.
   * @param int $timestamp
   *   The timestamp of the revision.
   * @param int|null $elements
   *   The number of elements of the revision, or NULL if the revision has no elements.
   * @param int|null $covered_elements
   *   The number of covered elements of the revision, or NULL if the revision has no elements.
   */
  public function addRevision(
    int $revision_id,
    int $timestamp,
    ?int $elements,
    ?int $covered_elements
  ): void {
    $this->revisions[$revision_id] = new FileNodeRevision(
      $this,
      $revision_id,
      $timestamp,
      $elements,
      $covered_elements
    );
  }

}
