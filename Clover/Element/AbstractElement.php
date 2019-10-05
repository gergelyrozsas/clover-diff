<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Clover\Element;

class AbstractElement {

  public function __construct(iterable $attributes = []) {
    foreach ($attributes as $attribute_name => $attribute_value) {
      if (\property_exists($this, $attribute_name)) {
        $this->{$attribute_name} = $attribute_value;
      }
    }
  }

}
