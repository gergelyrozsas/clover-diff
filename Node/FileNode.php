<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node;

/**
 * @codeCoverageIgnore
 */
class FileNode extends AbstractNode {

  public function __construct(array $path_elements, int $elements, int $covered_elements) {
    $this->path = $path_elements;
    $this->elements = $elements;
    $this->coveredElements = $covered_elements;
  }

}
