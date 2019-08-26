<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Element;

trait ForwardGetterTestTrait {

  /**
   * @dataProvider forwardGetterDataProvider
   */
  public function testForwardGetter(string $method_name, string $property_class, string $property, $value): void {
    $prophecy = $this->prophesize($property_class);
    $prophecy->{$method_name}()
      ->willReturn($value)
      ->shouldBeCalled();
    $this->setObjectProperty($this->unit, $property, $prophecy->reveal());
    $this->assertSame($value, $this->unit->{$method_name}());
  }

  public abstract function forwardGetterDataProvider(): iterable;

}
