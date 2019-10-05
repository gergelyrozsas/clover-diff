<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Utility;

use GergelyRozsas\CloverDiff\Utility\Path;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GergelyRozsas\CloverDiff\Utility\Path
 */
class PathTest extends TestCase {

  /**
   * @covers ::commonDirectory
   *
   * @dataProvider commonDirectoryDataProvider
   */
  public function testCommonDirectory(array $paths, string $expected) {
    $this->assertEquals($expected, Path::commonDirectory($paths));
  }

  public function commonDirectoryDataProvider(): iterable {
    $cases = [
      'case: paths array is empty' => [
        [],
        ''
      ],
      'case: no common prefix exist' => [
        ['a', 'b', 'c'],
        '',
      ],
      'case: common prefix exists' => [
        ['dir1/aaa', 'dir1/aab', 'dir1/abc'],
        'dir1/',
      ],
    ];
    return $cases;
  }

  /**
   * @dataProvider commonFilePathDataProvider
   */
  public function testCommonFilePath(array $paths, string $expected): void {
    $this->assertEquals($expected, Path::commonFilePath($paths));
  }

  public function commonFilePathDataProvider(): iterable {
    return [
      'case: paths array is empty' => [
        [],
        ''
      ],
      'case: no common file path exist' => [
        ['a', 'b', 'c'],
        '',
      ],
      'case: common file path exists' => [
        ['/some1/dir1/some/file/path', '/some2/dir2/some/file/path', 'some3/dir3/some/file/path'],
        '/some/file/path',
      ],
    ];
  }

  /**
   * @covers ::toUnixStyle
   *
   * @dataProvider unixStylePathConversionDataProvider
   */
  public function testUnixStylePathConversion(string $path, string $expected) {
    $this->assertEquals($expected, Path::toUnixStyle($path));
  }

  public function unixStylePathConversionDataProvider(): iterable {
    $cases = [
      'case: relative unix path' => [
        'no/leading/slash/in/path/name',
        'no/leading/slash/in/path/name',
      ],
      'case: relative windows path' => [
        'no\\leading\\slash\\in\\path\\name',
        'no/leading/slash/in/path/name',
      ],
      'case: absolute unix path' => [
        '/leading/slash/in/path/name',
        '/leading/slash/in/path/name',
      ],
      'case: absolute windows path' => [
        'Drive:\\leading\\slash\\in\\path\\name',
        '/Drive/leading/slash/in/path/name',
      ],
    ];
    return $cases;
  }

  /**
   * @dataProvider getPathToRootDataProvider
   */
  public function testGetPathToRoot(int $depth): void {
    $actual = Path::getPathToRoot($depth);
    $this->assertRegExp("/(\.\.\/){{$depth}}/", $actual);
  }

  public function getPathToRootDataProvider(): iterable {
    $cases = [
      [4],
      [100],
      [53],
      [29],
      [62],
    ];
    return $cases;
  }

  /**
   * @covers ::isEmptyPath
   *
   * @dataProvider isEmptyPathDataProvider
   */
  public function testIsEmptyPath(string $path, bool $expected): void {
    $this->assertEquals($expected, Path::isEmptyPath($path));
  }

  public function isEmptyPathDataProvider(): iterable {
    return [
      'empty string' => ['', TRUE],
      'zero string' => ['0', FALSE],
      'slash' => ['/', TRUE],
      'unix style' => ['/this/is/not/empty', FALSE],
      'windows style' => ['C:\\this\\is\\not\\empty\\either', FALSE],
    ];
  }

}
