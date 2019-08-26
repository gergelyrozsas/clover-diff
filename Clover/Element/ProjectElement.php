<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Element;

class ProjectElement extends AbstractElement {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\CoverageElement
   */
  protected $clover;

  /**
   * @var int
   */
  protected $timestamp;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\PackageElement[]
   */
  protected $packages = [];

  /**
   * @codeCoverageIgnore
   */
  public function getCoverage(): CoverageElement {
    return $this->clover;
  }

  /**
   * @codeCoverageIgnore
   */
  public function setCoverage(CoverageElement $clover): void {
    $this->clover = $clover;
  }

  /**
   * @codeCoverageIgnore
   */
  public function getTimestamp(): int {
    return (int) $this->timestamp;
  }

  /**
   * @codeCoverageIgnore
   */
  public function setTimestamp(int $timestamp): void {
    $this->timestamp = $timestamp;
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\PackageElement[]|iterable
   */
  public function getPackages(): iterable {
    foreach ($this->packages as $package) {
      yield $package->getName() => $package;
    }
  }

  public function addPackage(PackageElement $package) {
    if (!\in_array($package, $this->packages)) {
      $this->packages[] = $package;
      $package->setProject($this);
    }
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\FileElement[]|iterable
   */
  public function getFiles(): iterable {
    foreach ($this->packages as $package) {
      yield from $package->getFiles();
    }
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\ClassElement[]|iterable
   */
  public function getClasses(): iterable {
    foreach ($this->packages as $package) {
      yield from $package->getClasses();
    }
  }

}
