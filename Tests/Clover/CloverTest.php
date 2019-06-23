<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover;

use GergelyRozsas\CloverDiff\Clover\Clover;
use GergelyRozsas\CloverDiff\Clover\FileMetrics;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\Clover
 */
class CloverTest extends TestCase {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Clover
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new Clover();
  }

  /**
   * @dataProvider dataProvider
   */
  public function testUnit(array $case): void {
    foreach ($case as $step) {
      if (isset($step['file_to_add'])) {
        $this->unit->addFile(
          $step['file_to_add']['absolute_file_path'],
          $step['file_to_add']['file_metrics']
        );
      }

      $iterator = $this->unit->getFiles();
      $this->assertEquals($step['expected_get_files_iterator'], $iterator);

      foreach ($iterator as $relative_file_path => $file_metrics) {
        $this->assertSame($this->unit->getFile($relative_file_path), $file_metrics);
      }
      $this->assertNull($this->unit->getFile('any/other/relative/path'));
    }
  }

  public function dataProvider(): iterable {
    $cases = [
      'case: three files are added' => [
        [
          'baseline: no files are added yet' => [
            'expected_get_file_results' => [
              'any/relative/path' => NULL,
            ],
            'expected_get_files_iterator' => new \ArrayIterator([]),
          ],
          'step_1: only one file added, so the only valid relative path should be an empty string' => [
            'file_to_add' => [
              'absolute_file_path' => '/root/dir1/sub1/file1',
              'file_metrics' => $file_metrics_1 = $this->createFileMetrics()
            ],
            'expected_get_files_iterator' => new \ArrayIterator([
              '' => $file_metrics_1,
            ]),
          ],
          'step_2: another file is added in /root/dir1, so this directory should be the base of valid relative paths' => [
            'file_to_add' => [
              'absolute_file_path' => '/root/dir1/file2',
              'file_metrics' => $file_metrics_2 = $this->createFileMetrics()
            ],
            'expected_get_files_iterator' => new \ArrayIterator([
              'sub1/file1' => $file_metrics_1,
              'file2' => $file_metrics_2,
            ]),
          ],
          'step_3: another file is added in /root/dir1/sub1/sub_sub1, this should not have affect on the base of valid relative paths' => [
            'file_to_add' => [
              'absolute_file_path' => '/root/dir1/sub1/sub_sub1/file3',
              'file_metrics' => $file_metrics_3 = $this->createFileMetrics()
            ],
            'expected_get_files_iterator' => new \ArrayIterator([
              'sub1/file1' => $file_metrics_1,
              'file2' => $file_metrics_2,
              'sub1/sub_sub1/file3' => $file_metrics_3,
            ]),
          ],
        ],
      ],
    ];
    return $cases;
  }

  private function createFileMetrics(): FileMetrics {
    $file_metrics = $this->prophesize(FileMetrics::class);
    return $file_metrics->reveal();
  }

}
