<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Utility;

class Path {

  public static function commonDirectory(array $paths): string {
    if (empty($paths)) {
      return '';
    }

    $s1 = \min($paths);
    $s2 = \max($paths);
    $result = $s1;
    foreach (\str_split($s1) as $index => $character) {
      if ($character !== $s2[$index]) {
        $result = \substr($s1, 0, $index);
        break;
      }
    }

    $result = \preg_replace('#([^/\\\\]*)$#', '', $result);
    return $result;
  }

  public static function commonFilePath(array $paths): string {
    $reversed_paths = \array_map(function(string $path): string {
      return \strrev($path);
    }, $paths);

    $prefix = static::commonDirectory($reversed_paths);
    return \strrev($prefix);
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
    return \str_repeat('../', $depth);
  }

  public static function isEmptyPath(string $path): bool {
    $unix_style_path = self::toUnixStyle($path);
    return (('' === $unix_style_path) || ('/' === $unix_style_path));
  }

}
