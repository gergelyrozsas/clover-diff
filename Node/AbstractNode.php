<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node;

/**
 * @codeCoverageIgnore
 */
abstract class AbstractNode {

  protected $path = [];

  protected $elements = 0;

  protected $coveredElements = 0;

  public function getPath(): array {
    return $this->path;
  }

  public function getName(): string {
    return (string) \end($this->path);
  }

  public function getElements(): int {
    return $this->elements;
  }

  public function getCoveredElements(): int {
    return $this->coveredElements;
  }

}
