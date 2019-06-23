<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;

class CoverageBarRenderer {

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface
   */
  private $engine;

  public function __construct(
    EngineInterface $engine
  ) {
    $this->engine = $engine;
  }

  public function render(?float $percent, int $lo_upper_level, int $hi_lower_level): string {
    if (is_null($percent)) {
      return '';
    }

    return $this->engine->render('coverage_bar.html', [
      'level' => $this->getColorLevel($percent, $lo_upper_level, $hi_lower_level),
      'percent' => \sprintf('%.2F', $percent),
    ]);
  }

  private function getColorLevel(float $percent, int $lo_upper_level, int $hi_lower_level): string {
    if ($percent <= $lo_upper_level) {
      return 'danger';
    }

    if ($hi_lower_level <= $percent) {
      return 'success';
    }

    return 'warning';
  }

}
