<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Revision;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;

class DirectoryNodeRevision extends AbstractNodeRevision {

  /**
   * @codeCoverageIgnore
   */
  public function __construct(DirectoryNode $node, int $revision_id, int $timestamp) {
    $this->node = $node;
    $this->revisionId = $revision_id;
    $this->timestamp = $timestamp;
  }

  /**
   * Adds a file revision to the directory revision.
   *
   * @param \GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision $file_revision
   *   The file revision to add.
   */
  public function addFileNodeRevision(FileNodeRevision $file_revision) {
    if (NULL !== ($elements = $file_revision->getElements())) {
      $this->elements += $elements;
    }

    if (NULL !== ($covered_elements = $file_revision->getCoveredElements())) {
      $this->coveredElements += $covered_elements;
    }
  }

}
