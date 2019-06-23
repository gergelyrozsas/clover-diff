<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Utility;

class Path {

  public static function commonPrefix(array $paths): string {
    if (empty($paths)) {
      return '';
    }

    $s1 = \min($paths);
    $s2 = \max($paths);
    foreach (\str_split($s1) as $index => $character) {
      if ($character !== $s2[$index]) {
        return \substr($s1, 0, $index);
      }
    }

    return $s1;
  }

  public static function toUnixStyle(string $path): string {
    $replacements = [
      ':' => '',
      '\\' => '/',
    ];

    $unix_style_path = \str_replace(\array_keys($replacements), \array_values($replacements), $path);

    $is_absolute = (0 === \strpos($path, '/')) || (FALSE !== \strpos($path, ':'));
    if ($is_absolute) {
      $unix_style_path = '/' . \ltrim($unix_style_path, '/');
    }

    return $unix_style_path;
  }

  public static function getPathToRoot(int $depth): string {
    return str_repeat('../', $depth);
  }

}
