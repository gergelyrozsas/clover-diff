<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover;

use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Clover\Element\CoverageElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileElement;
use GergelyRozsas\CloverDiff\Clover\Element\FileMetricsElement;
use GergelyRozsas\CloverDiff\Clover\Element\PackageElement;
use GergelyRozsas\CloverDiff\Clover\Element\ProjectElement;
use GergelyRozsas\CloverDiff\Utility\IterableUtil;
use GergelyRozsas\CloverDiff\Utility\Path;

/**
 * @codeCoverageIgnore
 */
class Parser {

  public function parse(array $clover_file_paths): CloverCollection {
    $coverages = \array_map(function (string $clover_file_path): CoverageElement {
      return $this->doParse($clover_file_path);
    }, $clover_file_paths);
    \usort($coverages, function(CoverageElement $a, CoverageElement $b): int {
      return $a->getGenerated() - $b->getGenerated();
    });
    $clovers = \array_map(function (CoverageElement $coverage, int $revision_id): Clover {
      return new Clover($coverage, $revision_id);
    }, \array_values($coverages), \array_keys($coverages));
    return new CloverCollection($clovers);
  }

  private function doParse(string $clover_file_path): CoverageElement {
    $xml = \simplexml_load_file($clover_file_path);
    $coverage_elements = $xml->xpath('/coverage');
    $coverage_element = \reset($coverage_elements);
    return $this->parseCoverage($coverage_element);
  }

  private function parseCoverage(\SimpleXMLElement $coverage_element): CoverageElement {
    $coverage = new CoverageElement(
      $this->getAttributes($coverage_element)
    );
    $project_elements = $coverage_element->xpath('project');
    $project_element = \reset($project_elements);
    $project = $this->parseProject($project_element);
    $coverage->setProject($project);
    return $coverage;
  }

  private function parseProject(\SimpleXMLElement $project_element): ProjectElement {
    $project = new ProjectElement(
      $this->getAttributes($project_element)
    );
    $package_elements = $project_element->xpath('package');
    foreach ($package_elements as $package_element) {
      $package = $this->parsePackage($package_element);
      $project->addPackage($package);
    }
    return $project;
  }

  private function parsePackage(\SimpleXMLElement $package_element): PackageElement {
    $package = new PackageElement(
      $this->getAttributes($package_element)
    );
    $file_elements = $package_element->xpath('file');
    foreach ($file_elements as $file_element) {
      $file = $this->parseFile($file_element);
      $package->addFile($file);
    }
    return $package;
  }

  private function parseFile(\SimpleXMLElement $file_element): FileElement {
    $file = new FileElement(
      $this->getAttributes($file_element)
    );
    $file->setName(Path::toUnixStyle($file->getName()));
    $class_elements = $file_element->xpath('class');
    foreach ($class_elements as $class_element) {
      $class = $this->parseClass($class_element);
      $file->addClass($class);
    }
    $file_metrics_elements = $file_element->xpath('metrics');
    $file_metrics_element = \reset($file_metrics_elements);
    $file_metrics = $this->parseFileMetrics($file_metrics_element);
    $file->setMetrics($file_metrics);
    return $file;
  }

  private function parseClass(\SimpleXMLElement $class_element): ClassElement {
    return new ClassElement(
      $this->getAttributes($class_element)
    );
  }

  private function parseFileMetrics(\SimpleXMLElement $file_metrics_element): FileMetricsElement {
    return new FileMetricsElement(
      $this->getAttributes($file_metrics_element)
    );
  }

  private function getAttributes(\SimpleXMLElement $element): array {
    return \array_map(function ($attribute_value): string {
      return (string) $attribute_value;
    }, IterableUtil::iterableToArray($element->attributes()));
  }

}
