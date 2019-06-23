<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report;

use GergelyRozsas\CloverDiff\Diff\NodeDiff;
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
    ?DirectoryRenderer $directory_renderer = NULL,
    ?Filesystem $file_system = NULL
  ) {
    $this->directoryRenderer = $directory_renderer ?? new DirectoryRenderer();
    $this->fileSystem = $file_system ?? new Filesystem();
  }

  public function process(iterable $report, array $options = []): array {
    $options = $this->normalizeOptions($options);
    $this->copyAssets($options);

    foreach ($report as $diff) {
      if ($diff->isDirectoryNode()) {
        $this->fileSystem->dumpFile(
          $this->getIndexFilePath($options['target'], $diff),
          $this->directoryRenderer->render($diff, $options)
        );
      }
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

  private function getIndexFilePath(string $target, NodeDiff $diff): string {
    return \vsprintf('%s/%s/index.html', [
      $target,
      \implode('/', $diff->getPath()),
    ]);
  }

}
