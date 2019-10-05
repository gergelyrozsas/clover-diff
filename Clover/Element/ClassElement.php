<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Element;

class ClassElement extends AbstractElement {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\FileElement
   */
  protected $file;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $namespace;

  public function getCoverage(): CoverageElement {
    return $this->file->getCoverage();
  }

  public function getProject(): ProjectElement {
    return $this->file->getProject();
  }

  public function getPackage(): PackageElement {
    return $this->file->getPackage();
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function getFile(): FileElement {
    return $this->file;
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function setFile(FileElement $file): void {
    $this->file = $file;
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
   * {@codeCoverageIgnore}
   */
  public function getNamespace(): string {
    return $this->namespace;
  }

  /**
   * {@codeCoverageIgnore}
   */
  public function setNamespace(string $namespace): void {
    $this->namespace = $namespace;
  }

}
