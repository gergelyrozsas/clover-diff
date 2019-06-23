<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Diff\NodeDiff;
use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;
use GergelyRozsas\CloverDiff\Report\Html\Engine\PhpEngine;
use GergelyRozsas\CloverDiff\Utility\Path;
use GergelyRozsas\CloverDiff\Version;

class DirectoryRenderer {

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface
   */
  private $engine;

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Renderer\BreadcrumbRenderer
   */
  private $breadcrumbRenderer;

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Renderer\DirectoryItemRenderer
   */
  private $directoryItemRenderer;

  public function __construct(
    ?EngineInterface $engine = NULL,
    ?BreadcrumbRenderer $breadcrumb_renderer = NULL,
    ?DirectoryItemRenderer $directory_item_renderer = NULL
  ) {
    $this->engine = $engine ?? new PhpEngine();
    $this->breadcrumbRenderer = $breadcrumb_renderer ?? new BreadcrumbRenderer($this->engine);
    $this->directoryItemRenderer = $directory_item_renderer ?? new DirectoryItemRenderer($this->engine);
  }

  public function render(NodeDiff $diff, array $options = []): string {
    $items = [$this->directoryItemRenderer->render($diff, \array_merge($options, ['total' => TRUE]))];

    foreach ($this->getSortedChildren($diff) as $child) {
      $items[] = $this->directoryItemRenderer->render($child, $options);
    }

    $rendered = $this->engine->render('directory.html', [
      'full_path' => \implode('/', \array_merge(['root'], $diff->getPath())),
      'breadcrumbs' => $this->getBreadcrumbs($diff),
      'path_to_root' => $this->getPathToRoot($diff),
      'items' => $items,
      'low_upper_bound' => $options['lo_upper_level'],
      'high_lower_bound' => $options['hi_lower_level'],
      'diff_lo_upper_level' => $options['diff_lo_upper_level'],
      'diff_hi_lower_level' => $options['diff_hi_lower_level'],
      'date' => $this->formatDate($options['timestamp']),
      'version' => Version::id(),
    ]);

    return $rendered;
  }

  private function getSortedChildren(NodeDiff $diff): iterable {
    $children = \iterator_to_array($diff->getChildren());
    \uasort($children, function(NodeDiff $diff1, NodeDiff $diff2): int {
      $diff1_is_dir = (int) $diff1->isDirectoryNode();
      $diff2_is_dir = (int) $diff2->isDirectoryNode();
      if ($diff1_is_dir === $diff2_is_dir) {
        return \strcmp($diff1->getName(), $diff2->getName());
      }
      return $diff2_is_dir - $diff1_is_dir;
    });
    return new \ArrayIterator($children);
  }

  private function getPathToRoot(NodeDiff $diff) {
    return Path::getPathToRoot(\count($diff->getPath()));
  }

  private function getBreadcrumbs(NodeDiff $diff): string {
    return $this->breadcrumbRenderer->render($diff);
  }

  private function formatDate(int $timestamp): string {
    return \date('D M j G:i:s T Y', $timestamp);
  }

}
