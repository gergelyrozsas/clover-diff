<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover;

use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Clover\Element\CoverageElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Clover\Element\PackageElement;
use GergelyRozsas\CloverDiff\Clover\Element\ProjectElement;
use GergelyRozsas\CloverDiff\Utility\IterableUtil;

class Clover {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\CoverageElement
   */
  private $coverage;

  /**
   * @var int
   */
  private $revisionId;

  public function __construct(CoverageElement $coverage, int $revision_id) {
    $this->coverage = $coverage;
    $this->revisionId = $revision_id;
  }

  /**
   * @codeCoverageIgnore
   */
  public function getRevisionId(): int {
    return $this->revisionId;
  }

  public function getTimestamp(): int {
    return $this->coverage->getGenerated();
  }

  /**
   * @codeCoverageIgnore
   */
  public function getCoverage(): CoverageElement {
    return $this->coverage;
  }

  public function getProject(): ProjectElement {
    return $this->coverage->getProject();
  }

  public function getPackage(string $package_name): ?PackageElement {
    $packages = IterableUtil::iterableToArray($this->getPackages());
    return $packages[$package_name] ?? NULL;
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\PackageElement[]|iterable
   */
  public function getPackages(): iterable {
    return $this->coverage->getPackages();
  }

  public function getFile(string $file_name): ?FileElement {
    $files = IterableUtil::iterableToArray($this->getFiles());
    return $files[$file_name] ?? NULL;
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\FileElement[]|iterable
   */
  public function getFiles(): iterable {
    return $this->coverage->getFiles();
  }

  public function getClass(string $class_name): ?ClassElement {
    $classes = IterableUtil::iterableToArray($this->getClasses());
    return $classes[$class_name] ?? NULL;
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\ClassElement[]|iterable
   */
  public function getClasses(): iterable {
    return $this->coverage->getClasses();
  }

}
