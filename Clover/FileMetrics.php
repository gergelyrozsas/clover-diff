<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover;

/**
 * @codeCoverageIgnore
 */
class FileMetrics {

  /**
   * @var int
   */
  private $coveredElements;

  /**
   * @var int
   */
  private $elements;

  public function __construct(
    int $covered_elements = 0,
    int $elements = 0
  ) {
    $this->coveredElements = $covered_elements;
    $this->elements = $elements;
  }

  public function getCoveredElements(): int {
    return $this->coveredElements;
  }

  public function getElements(): int {
    return $this->elements;
  }

}
