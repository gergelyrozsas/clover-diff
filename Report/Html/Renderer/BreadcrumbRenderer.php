<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;
use GergelyRozsas\CloverDiff\Utility\Path;

class BreadcrumbRenderer {

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface
   */
  private $engine;

  public function __construct(
    EngineInterface $engine
  ) {
    $this->engine = $engine;
  }

  public function render(DirectoryNode $node): string {
    $path = array_merge(['root'], $node->getPath());
    $active = array_pop($path);
    $count = count($path);

    $breadcrumbs = [];
    for ($i = 0; $i < $count; $i++) {
      $breadcrumbs[] = $this->getInactiveBreadcrumb($path[$i], Path::getPathToRoot($count - $i));
    }
    $breadcrumbs[] = $this->getActiveBreadcrumb($active);

    return implode("\n", $breadcrumbs);
  }

  private function getActiveBreadcrumb(string $name): string {
    return $this->engine->render('breadcrumb_item.html', [
      'name' => $name,
      'active' => TRUE,
    ]);
  }

  private function getInactiveBreadcrumb(string $name, string $path_to_root): string {
    return $this->engine->render('breadcrumb_item.html', [
      'name' => $name,
      'active' => FALSE,
      'path_to_root' => $path_to_root,
    ]);
  }

}
