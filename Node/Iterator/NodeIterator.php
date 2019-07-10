<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Iterator;

use GergelyRozsas\CloverDiff\Node\NodeInterface;

class NodeIterator extends \ArrayIterator {

  public function __construct(NodeInterface $node) {
    parent::__construct($node->getChildren() ?? []);
  }

}
