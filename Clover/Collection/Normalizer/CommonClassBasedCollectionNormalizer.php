<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Collection\Normalizer;

use GergelyRozsas\CloverDiff\Clover\Clover;
use GergelyRozsas\CloverDiff\Clover\CloverCollection;
use GergelyRozsas\CloverDiff\Clover\Element\ClassElement;
use GergelyRozsas\CloverDiff\Utility\IterableUtil;
use GergelyRozsas\CloverDiff\Utility\Path;

class CommonClassBasedCollectionNormalizer implements CollectionNormalizerInterface {

  /**
   * {@inheritdoc}
   */
  public function supports(CloverCollection $clovers): bool {
    return !empty($this->getCommonClasses($clovers));
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public function normalize(CloverCollection $clovers) {
    $updated_root_directories = $this->getUpdatedRootDirectories($clovers);
    $this->updateFileNames($clovers, $updated_root_directories);
  }

  private function getCommonClasses(CloverCollection $clovers): array {
    $clover_classes_array = $clovers->map(function (Clover $clover): array {
      return IterableUtil::iterableToArray($clover->getClasses());
    });
    $intersect = \array_intersect_key(...$clover_classes_array);
    $common_class_names = \array_keys($intersect);
    $result = [];
    foreach ($common_class_names as $common_class_name) {
      foreach ($clover_classes_array as $clover_revision_id => $clover_classes) {
        $result[$common_class_name][$clover_revision_id] = $clover_classes[$common_class_name];
      }
    }
    return $result;
  }

  private function getUpdatedRootDirectories(CloverCollection $clovers): array {
    $common_classes = $this->getCommonClasses($clovers);
    $default_root_directories = $clovers->getRootDirectories();
    foreach ($common_classes as $classes) {
      if (\is_array($updated_root_directories = $this->doGetUpdatedRootDirectories($classes, $default_root_directories))) {
        return $updated_root_directories;
      }
    }
    return $default_root_directories;
  }

  private function doGetUpdatedRootDirectories(array $classes, array $default_root_directories): ?array {
    $updated_root_directories = [];
    $common_class_files = \array_map(function (ClassElement $class): string {
      return $class->getFile()->getName();
    }, $classes);
    $common_class_file_path = Path::commonFilePath($common_class_files);
    foreach ($common_class_files as $clover_revision_id => $common_class_file) {
      $updated_root_directories[$clover_revision_id] = \preg_replace("#{$common_class_file_path}$#", '/', $common_class_file);
      if (\strlen($default_root_directories[$clover_revision_id]) < \strlen($updated_root_directories[$clover_revision_id])) {
        return NULL;
      }
    }
    return $updated_root_directories;
  }

  private function updateFileNames(CloverCollection $clovers, array $updated_root_directories): void {
    $latest_clover_root_directory = \end($updated_root_directories);
    foreach ($clovers as $clover) {
      $clover_revision_id = $clover->getRevisionId();
      foreach ($clover->getFiles() as $file) {
        $modified_file_name = \preg_replace("#^{$updated_root_directories[$clover_revision_id]}#", $latest_clover_root_directory, $file->getName());
        $file->setName($modified_file_name);
      }
    }
  }

}
