<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Utility;

use GergelyRozsas\CloverDiff\Utility\Path;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GergelyRozsas\CloverDiff\Utility\Path
 */
class PathTest extends TestCase {

  /**
   * @covers ::commonPrefix
   *
   * @dataProvider commonPrefixDataProvider
   */
  public function testCommonPrefix(array $paths, string $expected) {
    $this->assertEquals($expected, Path::commonPrefix($paths));
  }

  public function commonPrefixDataProvider(): iterable {
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
        ['aaaa', 'aaab', 'abaa'],
        'a',
      ],
      'case: common prefix is the "lowest" string' => [
        ['aaa', 'aaabbb', 'aaabcd'],
        'aaa',
      ],
    ];
    return $cases;
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

}
