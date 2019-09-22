<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Element;

class PackageElement extends AbstractElement {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\ProjectElement
   */
  protected $project;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\FileElement[]
   */
  protected $files = [];

  public function getCoverage(): CoverageElement {
    return $this->project->getCoverage();
  }

  /**
   * @codeCoverageIgnore
   */
  public function getProject(): ProjectElement {
    return $this->project;
  }

  /**
   * @codeCoverageIgnore
   */
  public function setProject(ProjectElement $project): void {
    $this->project = $project;
  }

  /**
   * @codeCoverageIgnore
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @codeCoverageIgnore
   */
  public function setName(string $name): void {
    $this->name = $name;
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\FileElement[]|iterable
   */
  public function getFiles(): iterable {
    foreach ($this->files as $file) {
      yield $file->getName() => $file;
    }
  }

  public function addFile(FileElement $file) {
    if (!\in_array($file, $this->files)) {
      $this->files[] = $file;
      $file->setPackage($this);
    }
  }

  /**
   * @return \GergelyRozsas\CloverDiff\Clover\Element\ClassElement[]|iterable
   */
  public function getClasses(): iterable {
    foreach ($this->files as $file) {
      yield from $file->getClasses();
    }
  }

}
