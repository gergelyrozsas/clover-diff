<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node;

use GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface;

interface NodeInterface {

  /**
   * Gets the path of the node.
   *
   * @return array
   */
  public function getPath(): array;

  /**
   * Gets the name of the node.
   *
   * @return string
   */
  public function getName(): string;

  /**
   * Gets the timestamp of the node.
   *
   * @return int
   */
  public function getTimestamp(): int;

  /**
   * Gets the elements of the node.
   *
   * @return int|null
   *   A number if the node has elements, NULL otherwise.
   */
  public function getElements(): ?int;

  /**
   * Gets the covered elements of the node.
   *
   * @return int|null
   *   A number if the node has covered elements, NULL otherwise.
   */
  public function getCoveredElements(): ?int;

  /**
   * Gets the children of the node.
   *
   * @return array|null
   *   An array if the node has children, NULL otherwise.
   */
  public function getChildren(): ?array;

  /**
   * Gets whether the node has children.
   *
   * @return bool
   */
  public function hasChildren(): bool;

  /**
   * Gets a revision of the node.
   *
   * @param int $revision_id
   *   The id of the revision to get.
   *
   * @return \GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface
   *   The revision.
   *
   * @throws \OutOfBoundsException
   *   When the revision does not exist.
   */
  public function getRevision(int $revision_id): NodeRevisionInterface;

  /**
   * Gets all revisions of the node.
   *
   * @return \GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface[]
   */
  public function getRevisions(): array;

}
