<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Element;

class CoverageElement extends AbstractElement {

  /**
   * @var int
   */
  protected $generated;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\ProjectElement
   */
  protected $project;

  /**
   * @codeCoverageIgnore
   */
  public function getGenerated(): int {
    return (int) $this->generated;
  }

  /**
   * @codeCoverageIgnore
   */
  public function setGenerated(int $generated): void {
    $this->generated = $generated;
  }

  /**
   * @codeCoverageIgnore
   */
  public function getProject(): ProjectElement {
    return $this->project;
  }

  public function setProject(ProjectElement $project): void {
    $this->project = $project;
    $project->setCoverage($this);
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\PackageElement[]|iterable
   */
  public function getPackages(): iterable {
    return $this->project->getPackages();
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\FileElement[]|iterable
   */
  public function getFiles(): iterable {
    return $this->project->getFiles();
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\ClassElement[]|iterable
   */
  public function getClasses(): iterable {
    return $this->project->getClasses();
  }

}
