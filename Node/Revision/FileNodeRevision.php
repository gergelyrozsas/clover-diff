<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Revision;

use GergelyRozsas\CloverDiff\Node\FileNode;

/**
 * @codeCoverageIgnore
 */
class FileNodeRevision extends AbstractNodeRevision {

  public function __construct(
    FileNode $node,
    int $revision_id,
    int $timestamp,
    ?int $elements,
    ?int $covered_elements
  ) {
    $this->node = $node;
    $this->revisionId = $revision_id;
    $this->timestamp = $timestamp;
    $this->elements = $elements;
    $this->coveredElements = $covered_elements;
  }

}
