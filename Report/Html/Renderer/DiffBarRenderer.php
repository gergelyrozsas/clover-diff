<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;

class DiffBarRenderer {

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface
   */
  private $engine;

  public function __construct(
    EngineInterface $engine
  ) {
    $this->engine = $engine;
  }

  public function render(?float $diff_percent, int $diff_lo_upper_level, int $diff_hi_lower_level): string {
    // Below condition intentionally applies to NULL and 0 as in neither cases makes sense to render a diff bar.
    if (!$diff_percent) {
      return '';
    }

    return $this->engine->render('diff_bar.html', [
      'level' => $this->getDiffColorLevel($diff_percent, $diff_lo_upper_level, $diff_hi_lower_level),
      'percent' => \sprintf('%.2F', $diff_percent),
    ]);
  }

  public function getDiffColorLevel(?float $diff_percent, int $diff_lo_upper_level, int $diff_hi_lower_level): string {
    if (!$diff_percent) {
      return '';
    }

    if ($diff_percent <= $diff_lo_upper_level) {
      return 'danger';
    }

    if ($diff_hi_lower_level <= $diff_percent) {
      return 'success';
    }

    return 'warning';
  }

}
