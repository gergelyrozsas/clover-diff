<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Element;

/**
 * @codeCoverageIgnore
 */
class FileMetricsElement extends AbstractElement {

  /**
   * @var int
   */
  protected $coveredelements;

  /**
   * @var int
   */
  protected $elements;

  public function getCoveredElements(): int {
    return (int) $this->coveredelements;
  }

  public function setCoveredElements(int $covered_elements): void {
    $this->coveredelements = $covered_elements;
  }

  public function getElements(): int {
    return (int) $this->elements;
  }

  public function setElements(int $elements): void {
    $this->elements = $elements;
  }

}
