<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node;

use GergelyRozsas\CloverDiff\Node\Revision\DirectoryNodeRevision;
use GergelyRozsas\CloverDiff\Node\Revision\FileNodeRevision;

class DirectoryNode extends AbstractNode {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\NodeInterface[]
   */
  private $children = [];

  public function __construct(array $path = []) {
    $this->path = $path;
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function getChildren(): array {
    return $this->children;
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function hasChildren(): bool {
    return (bool) $this->children;
  }

  /**
   * Adds a file node to the directory node.
   *
   * @param \GergelyRozsas\CloverDiff\Node\FileNode $file
   *   The file node to add.
   */
  public function addFile(FileNode $file): void {
    $this->doAddFile([], $file->getPath(), $file);
    $this->addFileMetrics($file);
  }

  private function doAddFile(array $processed_path_elements, array $remaining_path_elements, FileNode $file): void {
    $child_name = \array_shift($remaining_path_elements);
    $child = &$this->children[$child_name];

    if ($remaining_path_elements) {
      \array_push($processed_path_elements, $child_name);
      $child = $child ?? new DirectoryNode($processed_path_elements);
      $child->doAddFile($processed_path_elements, $remaining_path_elements, $file);
      $child->addFileMetrics($file);
      return;
    }

    $child = $file;
  }

  private function addFileMetrics(FileNode $file): void {
    foreach ($file->getRevisions() as $revision) {
      $this->addFileRevisionMetrics($revision);
    }
  }

  private function addFileRevisionMetrics(FileNodeRevision $file_node_revision): void {
    $revision_id = $file_node_revision->getRevisionId();
    /** @var \GergelyRozsas\CloverDiff\Node\Revision\DirectoryNodeRevision $revision */
    $revision = &$this->revisions[$revision_id];
    $revision = $revision ?? new DirectoryNodeRevision($this, $revision_id, $file_node_revision->getTimestamp());
    $revision->addFileNodeRevision($file_node_revision);
  }

}
