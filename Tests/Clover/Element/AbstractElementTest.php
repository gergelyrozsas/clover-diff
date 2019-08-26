<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests\Clover\Element;

use GergelyRozsas\CloverDiff\Clover\Element\AbstractElement;
use GergelyRozsas\CloverDiff\Tests\AbstractTest;

/**
 * @covers \GergelyRozsas\CloverDiff\Clover\Element\AbstractElement
 */
class AbstractElementTest extends AbstractTest {

  /**
   * @var \GergelyRozsas\CloverDiff\Clover\Element\AbstractElement
   */
  private $unit;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->unit = new class(['property1' => 1234, 'property2' => 'some string']) extends AbstractElement {
      public $property1;
      public $property2;
    };
  }

  public function testConstructor(): void {
    $this->assertEquals(1234, $this->unit->property1);
    $this->assertEquals('some string', $this->unit->property2);
  }

}
