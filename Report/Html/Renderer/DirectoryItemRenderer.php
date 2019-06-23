<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Diff\NodeDiff;
use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;

class DirectoryItemRenderer {

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface
   */
  private $engine;

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Renderer\CoverageBarRenderer
   */
  private $coverageBarRenderer;

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Renderer\DiffBarRenderer
   */
  private $diffBarRenderer;

  public function __construct(
    EngineInterface $engine,
    ?CoverageBarRenderer $coverage_bar_renderer = NULL,
    ?DiffBarRenderer $diff_bar_renderer = NULL
  ) {
    $this->engine = $engine;
    $this->coverageBarRenderer = $coverage_bar_renderer ?? new CoverageBarRenderer($this->engine);
    $this->diffBarRenderer = $diff_bar_renderer ?? new DiffBarRenderer($this->engine);
  }

  public function render(NodeDiff $diff, array $options) {
    $diff_percent = $diff->getPercentageDiff();
    $data = array(
      'icon' => '',
      'name' => '',
      'old_lines_bar' => $this->getCoverageBar($diff->getOldPercentage(), $options),
      'old_lines_percentage' => $this->formatFloat($diff->getOldPercentage()),
      'old_lines_number' => $this->formatLinesNumbers($diff->getOldCoveredElements(), $diff->getOldElements()),
      'new_lines_bar' => $this->getCoverageBar($diff->getNewPercentage(), $options),
      'new_lines_percentage' => $this->formatFloat($diff->getNewPercentage()),
      'new_lines_number' => $this->formatLinesNumbers($diff->getNewCoveredElements(), $diff->getNewElements()),
      'diff_percentage' => $this->formatFloat($diff_percent, '+'),
      'diff_bar' => $this->getDiffBar($diff_percent, $options),
      'diff_level' => $this->getDiffColorLevel($diff_percent, $options),
    );

    if (!empty($options['total'])) {
      $data['name'] = 'Total';
    }
    elseif ($diff->isDirectoryNode()) {
      $data['icon'] = '<span class="glyphicon glyphicon-folder-open"></span> ';
      $data['name'] = \vsprintf('<a href="%s/index.html">%s</a>', [
        $diff->getName(),
        $diff->getName(),
      ]);
    }
    else {
      $data['icon'] = '<span class="glyphicon glyphicon-file"></span> ';
      $data['name'] = $diff->getName();
    }

    return $data;
  }

  private function getCoverageBar(?float $percent, array $options): string {
    return $this->coverageBarRenderer->render(
      $percent,
      $options['lo_upper_level'],
      $options['hi_lower_level']
    );
  }

  private function formatFloat(?float $value, string $sign = ''): string {
    return \is_null($value) ? 'n/a' : \sprintf("%{$sign}.2f%%", $value);
  }

  private function formatLinesNumbers(?int $covered_elements, ?int $elements): string {
    if (\is_null($covered_elements) || !$elements) {
      return 'n/a';
    }

    return "{$this->formatInt($covered_elements)} / {$this->formatInt($elements)}";
  }

  private function formatInt(int $value): string {
    return \sprintf('%d', $value);
  }

  private function getDiffBar(?float $diff_percent, array $options): string {
    return $this->diffBarRenderer->render(
      $diff_percent,
      $options['diff_lo_upper_level'],
      $options['diff_hi_lower_level']
    );
  }

  private function getDiffColorLevel(?float $diff_percent, array $options): string {
    return $this->diffBarRenderer->getDiffColorLevel(
      $diff_percent,
      $options['diff_lo_upper_level'],
      $options['diff_hi_lower_level']
    );
  }

}
