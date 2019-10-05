<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Node\Iterator\NodeIterator;
use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\Iterator\SortableIterator;
use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;
use GergelyRozsas\CloverDiff\Report\Html\Utility\NodeSort;
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
    EngineInterface $engine,
    BreadcrumbRenderer $breadcrumb_renderer,
    DirectoryItemRenderer $directory_item_renderer
  ) {
    $this->engine = $engine;
    $this->breadcrumbRenderer = $breadcrumb_renderer;
    $this->directoryItemRenderer = $directory_item_renderer;
  }

  public function render(DirectoryNode $node, array $options = []): string {
    $items = [$this->directoryItemRenderer->render($node, \array_merge($options, ['total' => TRUE]))];

    foreach ($this->getIterator($node) as $child_directory_node) {
      $items[] = $this->directoryItemRenderer->render($child_directory_node, $options);
    }

    $rendered = $this->engine->render('directory.html', [
      'full_path' => \implode('/', \array_merge(['root'], $node->getPath())),
      'breadcrumbs' => $this->getBreadcrumbs($node),
      'path_to_root' => $this->getPathToRoot($node),
      'revisions' => $node->getRevisions(),
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

  private function getIterator(DirectoryNode $node): iterable {
    $iterator = new NodeIterator($node);
    $iterator = new SortableIterator($iterator, NodeSort::sortByType());
    return $iterator;
  }

  private function getPathToRoot(DirectoryNode $node) {
    return Path::getPathToRoot(\count($node->getPath()));
  }

  private function getBreadcrumbs(DirectoryNode $node): string {
    return $this->breadcrumbRenderer->render($node);
  }

  private function formatDate(int $timestamp): string {
    return \date('D M j G:i:s T Y', $timestamp);
  }

}
