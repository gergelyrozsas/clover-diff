<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Utility;

use GergelyRozsas\CloverDiff\Node\DirectoryNode;
use GergelyRozsas\CloverDiff\Node\NodeInterface;

class NodeSort {

  /**
   * Returns a function that sorts an array of nodes first by type and then by name.
   *
   * @return callable
   */
  public static function sortByType(): callable {
    return function (NodeInterface $node_1, NodeInterface $node_2): int {
      $node_1_is_dir = (int) ($node_1 instanceof DirectoryNode);
      $node_2_is_dir = (int) ($node_2 instanceof DirectoryNode);
      if ($node_1_is_dir === $node_2_is_dir) {
        return \strcmp($node_1->getName(), $node_2->getName());
      }
      return $node_2_is_dir - $node_1_is_dir;
    };
  }

}
