<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff;

use GergelyRozsas\CloverDiff\Clover\CloverCollection;
use GergelyRozsas\CloverDiff\Clover\Parser;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\DelegatingCollectionNormalizer;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CommonClassBasedCollectionNormalizer;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\EquivalentRootDirectoryBasedCollectionNormalizer;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolver;
use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\FileNode;
use GergelyRozsas\CloverDiff\Utility\Path;

/**
 * @codeCoverageIgnore
 */
class CloverDiff {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Parser
   */
  private $parser;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerInterface
   */
  private $normalizer;

  public function __construct(
    ?Parser $parser = NULL,
    ?CollectionNormalizerInterface $normalizer = NULL
  ) {
    $this->parser = $parser ?? new Parser();
    $this->normalizer = $normalizer ?? new DelegatingCollectionNormalizer(
      new CollectionNormalizerResolver([
        20 => [new EquivalentRootDirectoryBasedCollectionNormalizer()],
        10 => [new CommonClassBasedCollectionNormalizer()],
      ])
    );
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
    $this->normalizeClovers($clovers);
    return $this->buildDirectoryNode($clovers);
  }

  private function parseCloverFiles(array $clover_file_paths): CloverCollection {
    return $this->parser->parse($clover_file_paths);
  }

  private function normalizeClovers(CloverCollection $clovers): void {
    $this->doNormalizeClovers($clovers);
    $overall_common_directory = $this->getOverallCommonDirectory($clovers);
    $this->convertFilePathsToRelative($clovers, $overall_common_directory);
  }

  private function doNormalizeClovers(CloverCollection $clovers): void {
    if (!$this->normalizer->supports($clovers)) {
      throw new \RuntimeException('Your Clovers cannot be compared. No suitable normalizer was found.');
    }

    $this->normalizer->normalize($clovers);
  }

  private function getOverallCommonDirectory(CloverCollection $clovers): string {
    $file_names = [];
    foreach ($clovers->getFiles() as $file) {
      $file_names[] = $file->getName();
    }
    $overall_common_directory = Path::commonDirectory($file_names);
    if (Path::isEmptyPath($overall_common_directory)) {
      throw new \InvalidArgumentException('Your Clovers cannot be compared. The selected normalizer did not convert all of the file names.');
    }
    return $overall_common_directory;
  }

  private function convertFilePathsToRelative(CloverCollection $clovers, string $overall_common_directory): void {
    foreach ($clovers->getFiles() as $file) {
      $relative_file_name = \preg_replace("#^{$overall_common_directory}#", '', $file->getName());
      $file->setName($relative_file_name);
    }
  }

  private function buildDirectoryNode(CloverCollection $clovers): DirectoryNode {
    $node = new DirectoryNode();
    foreach ($clovers->getLatest()->getFiles() as $file) {
      $file_node = $this->buildFileNode($file->getName(), $clovers);
      $node->addFile($file_node);
    }
    return $node;
  }

  private function buildFileNode(string $file_name, CloverCollection $clovers): FileNode {
    $path = \explode('/', $file_name);
    $file = new FileNode($path);
    foreach ($clovers as $clover) {
      $clover_file = $clover->getFile($file_name);
      $file->addRevision(
        $clover->getRevisionId(),
        $clover->getTimestamp(),
        $clover_file ? $clover_file->getMetrics()->getElements() : NULL,
        $clover_file ? $clover_file->getMetrics()->getCoveredElements() : NULL
      );
    }
    return $file;
  }

}
