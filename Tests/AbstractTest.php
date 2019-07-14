<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase {

  protected function setObjectProperty($object, string $property, $value): void {
    $reflection = new \ReflectionObject($object);
    $reflection_property = $reflection->getProperty($property);
    $accessible = $reflection_property->isPublic();
    $reflection_property->setAccessible(TRUE);
    $reflection_property->setValue($object, $value);
    $reflection_property->setAccessible($accessible);
  }

}
