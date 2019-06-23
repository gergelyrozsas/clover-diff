<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff;

use GergelyRozsas\CloverDiff\Clover\Clover;
use GergelyRozsas\CloverDiff\Clover\FileMetrics;
use GergelyRozsas\CloverDiff\Clover\Parser;
use GergelyRozsas\CloverDiff\Diff\RecursiveNodeDiffIterator;
use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;

/**
 * @codeCoverageIgnore
 */
class CloverDiff {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Parser
   */
  private $parser;

  public function __construct(
    ?Parser $parser = NULL
  ) {
    $this->parser = $parser ?? new Parser();
  }

  /**
   * Compares two Clover files and return an iterable report.
   *
   * @param $clover_1_file_path string
   *   The path of the first Clover file.
   * @param $clover_2_file_path string
   *   The path of the second Clover file.
   *
   * @return \GergelyRozsas\CloverDiff\Diff\NodeDiff[]|\RecursiveIteratorIterator
   */
  public function compare(string $clover_1_file_path, string $clover_2_file_path): iterable {
    $clover_1 = $this->parser->parse($clover_1_file_path);
    $clover_2 = $this->parser->parse($clover_2_file_path);
    $iterator = $this->prepareRecursiveNodeDiffIterator($clover_1, $clover_2);
    return new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
  }

  private function prepareRecursiveNodeDiffIterator(Clover $clover_1, Clover $clover_2): RecursiveNodeDiffIterator {
    $newer_clover = $clover_1->getTimestamp() <= $clover_2->getTimestamp() ? $clover_2 : $clover_1;
    $older_clover = $clover_1->getTimestamp() <= $clover_2->getTimestamp() ? $clover_1 : $clover_2;

    $newer_node = new DirectoryNode();
    $older_node = new DirectoryNode();

    foreach ($newer_clover->getFiles() as $file_name => $new_metrics) {
      $path = \explode('/', $file_name);
      $newer_node->addFile($this->createFileNode($path, $new_metrics));
      if ($old_metrics = $older_clover->getFile($file_name)) {
        $older_node->addFile($this->createFileNode($path, $old_metrics));
      }
    }

    return new RecursiveNodeDiffIterator(['root' => $newer_node], ['root' => $older_node]);
  }

  private function createFileNode(array $path, FileMetrics $file_metrics): FileNode {
    return new FileNode($path, $file_metrics->getElements(), $file_metrics->getCoveredElements());
  }

}
