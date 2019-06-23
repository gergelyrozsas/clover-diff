<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node;

class DirectoryNode extends AbstractNode {

  /**
   * @var \GergelyRozsas\CloverDiff\Node\AbstractNode[]
   */
  public $children = [];

  public function __construct(array $path = []) {
    $this->path = $path;
  }

  /**
   * @codeCoverageIgnore
   */
  public function getChildren(): array {
    return $this->children;
  }

  public function getChild(array $path_elements): ?AbstractNode {
    if (empty($path_elements)) {
      return NULL;
    }

    $name = \array_shift($path_elements);
    if (!isset($this->children[$name])) {
      return NULL;
    }

    $child = $this->children[$name];
    if (!$path_elements) {
      return $child;
    }

    return ($child instanceof DirectoryNode) ? $child->getChild($path_elements) : NULL;
  }

  /**
   * @codeCoverageIgnore
   */
  public function hasChildren(): bool {
    return (bool) $this->children;
  }

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
    $this->elements += $file->getElements();
    $this->coveredElements += $file->getCoveredElements();
  }

}
