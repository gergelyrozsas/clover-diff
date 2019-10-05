<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests;

use GergelyRozsas\CloverDiff\CloverDiff;
use GergelyRozsas\CloverDiff\Factory;
use GergelyRozsas\CloverDiff\Report\Html;
use Psr\Container\ContainerInterface;

/**
 * @covers \GergelyRozsas\CloverDiff\Factory
 */
class FactoryTest extends AbstractTest {

  /**
   * @dataProvider getDataProvider
   */
  public function testGet(
    ?ContainerInterface $container,
    string $class
  ): void {
    if ($container) {
      Factory::setContainer($container);
    }
    $this->assertInstanceOf($class, Factory::get($class));
  }

  public function getDataProvider(): iterable {
    return [
      'default container' => [
        'container' => NULL,
        'class' => CloverDiff::class,
      ],
      'custom container' => [
        'container' => $this->createContainer(Html::class),
        'class' => Html::class,
      ],
    ];
  }

  private function createContainer(string $contained_class): ContainerInterface {
    /** @var object $instance */
    $instance = $this->prophesize($contained_class);
    /** @var \Psr\Container\ContainerInterface|\Prophecy\Prophecy\ObjectProphecy $container */
    $container = $this->prophesize(ContainerInterface::class);
    $container->get($contained_class)
      ->willReturn($instance);
    return $container->reveal();
  }

}
