<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff;

use GergelyRozsas\CloverDiff\DependencyInjection\Container;
use GergelyRozsas\CloverDiff\Report\Html;
use Psr\Container\ContainerInterface;

abstract class Factory {

  /**
   * @var string
   */
  protected static $defaultContainerClass = Container::class;

  /**
   * @var \Psr\Container\ContainerInterface
   */
  protected static $container;

  public static function setContainer(ContainerInterface $container): void {
    static::$container = $container;
  }

  public static function get(string $class): object {
    return static::getContainer()->get($class);
  }

  /**
   * @codeCoverageIgnore
   */
  public static function getCloverDiff(): CloverDiff {
    return static::get(CloverDiff::class);
  }

  /**
   * @codeCoverageIgnore
   */
  public static function getHtmlReport(): Html {
    return static::get(Html::class);
  }

  protected static function getContainer(): ContainerInterface {
    static::$container = static::$container ?? new static::$defaultContainerClass();
    return static::$container;
  }

}
