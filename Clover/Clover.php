<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover;

use GergelyRozsas\CloverDiff\Utility\Path;

class Clover {

  /**
   * @var int
   */
  private $timestamp = 0;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\FileMetrics[]
   */
  private $files = [];

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\FileMetrics[]
   */
  private $normalizedFiles = [];

  /**
   * @codeCoverageIgnore
   */
  public function getTimestamp(): int {
    return $this->timestamp;
  }

  /**
   * @codeCoverageIgnore
   */
  public function setTimestamp(int $timestamp): void {
    $this->timestamp = $timestamp;
  }

  public function addFile(string $absolute_file_path, FileMetrics $file_metrics): void {
    $this->normalizedFiles = NULL;
    $this->files[$absolute_file_path] = $file_metrics;
  }

  public function getFile(string $relative_file_path): ?FileMetrics {
    $this->normalizeFiles();
    return $this->normalizedFiles[$relative_file_path] ?? NULL;
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\FileMetrics[]
   */
  public function getFiles(): iterable {
    $this->normalizeFiles();
    return new \ArrayIterator($this->normalizedFiles);
  }

  private function normalizeFiles(): void {
    if (NULL === $this->normalizedFiles) {
      $this->normalizedFiles = [];
      $common_prefix = Path::commonPrefix(\array_keys($this->files));
      $common_prefix_length = \strlen($common_prefix);
      foreach ($this->files as $absolutefile_path => $file) {
        $relative_file_path = \substr($absolutefile_path, $common_prefix_length);
        $this->normalizedFiles[$relative_file_path] = $file;
      }
    }
  }

}
