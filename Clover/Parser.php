<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover;

use GergelyRozsas\CloverDiff\Utility\Path;

/**
 * @codeCoverageIgnore
 */
class Parser {

  /**
   * {@inheritdoc}
   */
  public function parse(string $clover_file_path): Clover {
    $result = new Clover();
    $xml = \simplexml_load_file($clover_file_path);
    $result->setTimestamp((int) (string) $xml->xpath('/coverage')[0]->attributes()['generated']);
    $files = $xml->xpath('/coverage/project//file');
    foreach ($files as $file) {
      $original_file_path = (string) $file->attributes()['name'];
      $unix_style_file_path = Path::toUnixStyle($original_file_path);
      $metrics = $file->xpath('metrics');
      foreach ($metrics as $metric) {
        $attributes = $metric->attributes();
        $result->addFile($unix_style_file_path, new FileMetrics((int) (string) $attributes['coveredelements'], (int) (string) $attributes['elements']));
      }
    }
    return $result;
  }

}
