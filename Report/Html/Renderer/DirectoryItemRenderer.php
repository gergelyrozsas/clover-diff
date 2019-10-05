<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;
use GergelyRozsas\CloverDiff\Report\Html\Utility\NodeMath;

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
    CoverageBarRenderer $coverage_bar_renderer,
    DiffBarRenderer $diff_bar_renderer
  ) {
    $this->engine = $engine;
    $this->coverageBarRenderer = $coverage_bar_renderer;
    $this->diffBarRenderer = $diff_bar_renderer;
  }

  public function render(NodeInterface $node, array $options) {
    $diff_percent = NodeMath::getPercentageDiff(
      $node->getRevision(-1),
      $node->getRevision(0)
    );
    $data = array(
      'icon' => '',
      'name' => '',
      'revisions' => $this->getRevisions($node, $options),
      'diff_percentage' => $this->formatFloat($diff_percent, '+'),
      'diff_bar' => $this->getDiffBar($diff_percent, $options),
      'diff_level' => $this->getDiffColorLevel($diff_percent, $options),
    );

    if (!empty($options['total'])) {
      $data['name'] = 'Total';
    }
    elseif ($node->hasChildren()) {
      $data['icon'] = '<span class="glyphicon glyphicon-folder-open"></span> ';
      $data['name'] = \vsprintf('<a href="%s/index.html">%s</a>', [
        $node->getName(),
        $node->getName(),
      ]);
    }
    else {
      $data['icon'] = '<span class="glyphicon glyphicon-file"></span> ';
      $data['name'] = $node->getName();
    }

    return $data;
  }

  private function getRevisions(NodeInterface $node, array $options): array {
    $output = [];
    $revisions = $node->getRevisions();
    foreach ($revisions as $revision_id => $revision) {
      $percentage = NodeMath::getPercentage($revision);
      $output[] = [
        'bar' => $this->getCoverageBar($percentage, $options),
        'percentage' => $this->formatFloat($percentage),
        'lines_number' => $this->formatLinesNumbers($revision->getCoveredElements(), $revision->getElements()),
      ];
    }
    return $output;
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
