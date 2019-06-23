<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Report\Html\Renderer;

use GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface;
use GergelyRozsas\CloverDiff\Report\Html\Renderer\CoverageBarRenderer;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @covers \GergelyRozsas\CloverDiff\Report\Html\Renderer\CoverageBarRenderer
 */
class CoverageBarRendererTest extends TestCase {

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Renderer\CoverageBarRenderer
   */
  private $unit;

  /**
   * @var \GergelyRozsas\CloverDiff\Report\Html\Engine\EngineInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  private $engine;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->engine = $this->prophesize(EngineInterface::class);
    $this->unit = new CoverageBarRenderer(
      $this->engine->reveal()
    );
  }

  /**
   * @dataProvider renderDataProvider
   */
  public function testRender(
    ?float $percent,
    int $lo_upper_level,
    int $hi_lower_level,
    ?array $expected_options,
    string $expected_result
  ) {
    if (NULL === $expected_options) {
      $this->engine->render(Argument::any(), Argument::any())
        ->shouldNotBeCalled();
    }
    else {
      $this->engine->render('coverage_bar.html', $expected_options)
        ->willReturn($expected_result)
        ->shouldBeCalled();
    }

    $result = $this->unit->render($percent, $lo_upper_level, $hi_lower_level);
    $this->assertEquals($expected_result, $result);
  }

  public function renderDataProvider(): iterable {
    $cases = [
      'case: percent is null' => [
        NULL,
        1,
        3,
        NULL,
        '',
      ],
      'case: percent is in danger zone' => [
        13,
        20,
        70,
        [
          'level' => 'danger',
          'percent' => '13.00',
        ],
        'sample output from EngineInterface::render',
      ],
      'case: percent is in warning zone' => [
        45,
        30,
        50,
        [
          'level' => 'warning',
          'percent' => '45.00',
        ],
        'sample output from EngineInterface::render',
      ],
      'case: percentage is in success zone' => [
        99,
        10,
        90,
        [
          'level' => 'success',
          'percent' => '99.00',
        ],
        'sample output from EngineInterface::render',
      ],
    ];
    return $cases;
  }

}
