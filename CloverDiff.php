<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff;

use GergelyRozsas\CloverDiff\Clover\Clover;
use GergelyRozsas\CloverDiff\Clover\Parser;
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
   * Compares Clover files and returns a comparison object.
   *
   * @param array $clover_file_paths
   *   An array of clover file paths to compare.
   *
   * @throws \InvalidArgumentException
   *   When less than two file paths are supplied.
   *
   * @return \GergelyRozsas\CloverDiff\Node\DirectoryNode
   */
  public function compare(array $clover_file_paths): DirectoryNode {
    if (\count($clover_file_paths) < 2) {
      throw new \InvalidArgumentException('At least two Clover file paths must be specified.');
    }

    $clovers = $this->parseCloverFiles($clover_file_paths);
    $clovers = $this->sortCloversByTimestampAscending($clovers);
    $directory = $this->buildDirectoryNode($clovers);
    return $directory;
  }

  private function parseCloverFiles(array $clover_file_paths): array {
    $clovers = \array_map(function(string $clover_file_path): Clover {
      return $this->parser->parse($clover_file_path);
    }, $clover_file_paths);
    return $clovers;
  }

  private function sortCloversByTimestampAscending(array $clovers): array {
    \usort($clovers, function(Clover $a, Clover $b): int {
      return $a->getTimestamp() - $b->getTimestamp();
    });
    return $clovers;
  }

  private function buildDirectoryNode(array $clovers): DirectoryNode {
    /** @var \GergelyRozsas\CloverDiff\Clover\Clover[] $clovers */
    $latest = \end($clovers);
    $node = new DirectoryNode();
    foreach ($latest->getFiles() as $file_name => $file_metrics) {
      $file = $this->buildFileNode($file_name, $clovers);
      $node->addFile($file);
    }
    return $node;
  }

  private function buildFileNode(string $file_name, array $clovers): FileNode {
    /** @var \GergelyRozsas\CloverDiff\Clover\Clover[] $clovers */
    $path = \explode('/', $file_name);
    $file = new FileNode($path);
    foreach ($clovers as $revision_id => $clover) {
      $revision_metrics = $clover->getFile($file_name);
      $file->addRevision(
        $revision_id,
        $clover->getTimestamp(),
        $revision_metrics ? $revision_metrics->getElements() : NULL,
        $revision_metrics ? $revision_metrics->getCoveredElements() : NULL
      );
    }
    return $file;
  }

}
