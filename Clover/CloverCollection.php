<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover;

use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Utility\IterableUtil;
use GergelyRozsas\CloverDiff\Utility\Path;

class CloverCollection implements \IteratorAggregate {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Clover[]
   */
  private $cloversByRevision = [];

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Clover[]
   */
  private $cloversByTimestamp = [];

  public function __construct(array $clovers) {
    /** @var \GergelyRozsas\CloverDiff\Clover\Clover[] $clovers */
    foreach ($clovers as $clover) {
      $this->cloversByRevision[$clover->getRevisionId()] = $clover;
      $this->cloversByTimestamp[$clover->getTimestamp()] = $clover;
    }
  }

  public function getCloverByRevision(int $revision): Clover {
    $revision = ($revision < 0 ? $revision + \count($this->cloversByRevision) : $revision);
    if (!isset($this->cloversByRevision[$revision])) {
      throw new \OutOfBoundsException();
    }
    return $this->cloversByRevision[$revision];
  }

  public function getCloverByTimestamp(int $timestamp): Clover {
    if (!isset($this->cloversByTimestamp[$timestamp])) {
      throw new \OutOfBoundsException();
    }
    return $this->cloversByTimestamp[$timestamp];
  }

  public function getLatest(): Clover {
    return $this->getCloverByRevision(-1);
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function map(callable $callable): array {
    return \array_map($callable, $this->cloversByRevision);
  }

  public function getRootDirectories(): array {
    return $this->map(function (Clover $clover): string {
      $file_paths = \array_map(function (FileElement $file): string {
        return $file->getName();
      }, IterableUtil::iterableToArray($clover->getFiles()));
      return Path::commonDirectory($file_paths);
    });
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\PackageElement[]|iterable
   */
  public function getPackages(): iterable {
    foreach ($this->cloversByRevision as $clover) {
      yield from $clover->getPackages();
    }
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\FileElement[]|iterable
   */
  public function getFiles(): iterable {
    foreach ($this->cloversByRevision as $clover) {
      yield from $clover->getFiles();
    }
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\ClassElement[]|iterable
   */
  public function getClasses(): iterable {
    foreach ($this->cloversByRevision as $clover) {
      yield from $clover->getClasses();
    }
  }

  /**
   * {inheritdoc}
   *
   * @return \GergelyRozsas\CloverDiff\Clover\Clover[]|iterable
   */
  public function getIterator(): iterable {
    return new \ArrayIterator($this->cloversByRevision);
  }

}
