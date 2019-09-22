<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Element;

class FileElement extends AbstractElement {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\PackageElement
   */
  protected $package;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\ClassElement[]
   */
  protected $classes = [];

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\FileMetricsElement
   */
  protected $metrics;

  public function getCoverage(): CoverageElement {
    return $this->package->getCoverage();
  }

  public function getProject(): ProjectElement {
    return $this->package->getProject();
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function getPackage(): PackageElement {
    return $this->package;
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function setPackage(PackageElement $package): void {
    $this->package = $package;
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function setName(string $name): void {
    $this->name = $name;
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\ClassElement[]|iterable
   */
  public function getClasses(): iterable {
    foreach ($this->classes as $class) {
      yield $class->getName() => $class;
    }
  }

  public function addClass(ClassElement $class) {
    if (!\in_array($class, $this->classes)) {
      $this->classes[] = $class;
      $class->setFile($this);
    }
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function getMetrics(): FileMetricsElement {
    return $this->metrics;
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function setMetrics(FileMetricsElement $metrics): void {
    $this->metrics = $metrics;
  }

}
