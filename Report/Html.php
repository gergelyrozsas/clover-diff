<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\Iterator\DirectoryOnlyFilterIterator;
use GergelyRozsas\CloverDiff\Node\Iterator\RecursiveNodeIterator;
use GergelyRozsas\CloverDiff\Node\NodeInterface;
use GergelyRozsas\CloverDiff\Report\Html\Renderer\DirectoryRenderer;
use Symfony\Component\Filesystem\Filesystem;

class Html {

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Renderer\DirectoryRenderer
   */
  private $directoryRenderer;

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  private $fileSystem;

  public function __construct(
    DirectoryRenderer $directory_renderer,
    Filesystem $file_system
  ) {
    $this->directoryRenderer = $directory_renderer;
    $this->fileSystem = $file_system;
  }

  public function process(DirectoryNode $node, array $options = []): array {
    $options = $this->normalizeOptions($options);
    $this->copyAssets($options);

    $this->dumpDirectoryPage($node, $options);
    foreach ($this->getSubdirectoryNodes($node) as $subdirectory_node) {
      $this->dumpDirectoryPage($subdirectory_node, $options);
    }

    return $options;
  }

  private function normalizeOptions(array $options): array {
    $options = \array_merge([
      'target' => $this->fileSystem->tempnam('clover_diff', 'prefix'),
      'assets_dir' => __DIR__ . '/../Resources/assets',
      'lo_upper_level' => 50,
      'hi_lower_level' => 90,
      'diff_lo_upper_level' => -5,
      'diff_hi_lower_level' => 5,
      'timestamp' => $_SERVER['REQUEST_TIME'] ?? \time(),
    ], $options);
    $options['target'] = $this->normalizeDirectoryName($options['target']);
    return $options;
  }

  private function normalizeDirectoryName(string $directory): string {
    return \rtrim($directory, '/\\');
  }

  private function copyAssets(array $options): void {
    $this->fileSystem->mirror($options['assets_dir'], "{$options['target']}/.assets");
  }

  private function dumpDirectoryPage(DirectoryNode $node, array $options): void {
    $this->fileSystem->dumpFile(
      $this->getIndexFilePath($node, $options['target']),
      $this->directoryRenderer->render($node, $options)
    );
  }

  private function getIndexFilePath(NodeInterface $node, string $target): string {
    return \vsprintf('%s/%s/index.html', [
      $target,
      \implode('/', $node->getPath()),
    ]);
  }

  /**
   * @param \GergelyRozsas\CloverDiff\Node\DirectoryNode $node
   *
   * @return \GergelyRozsas\CloverDiff\Node\DirectoryNode[]
   */
  private function getSubdirectoryNodes(DirectoryNode $node): iterable {
    $iterator = new RecursiveNodeIterator($node);
    $iterator = new DirectoryOnlyFilterIterator($iterator);
    $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
    return $iterator;
  }

}
