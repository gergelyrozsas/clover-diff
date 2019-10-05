<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Utility;

use GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface;

class NodeMath {

  /**
   * Returns the coverage percentage for a node revision.
   *
   * @param \GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface $revision
   *   The node revision.
   *
   * @return float|null
   *   A float value if the node revision has a valid coverage percentage, NULL otherwise.
   */
  public static function getPercentage(NodeRevisionInterface $revision): ?float {
    return self::doGetPercentage(
      $revision->getCoveredElements(),
      $revision->getElements()
    );
  }

  /**
   * Returns the coverage percentage difference between two node revisions.
   *
   * The difference is relative to the second node revision provided.
   *
   * @param \GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface $revision_1
   *   The first node revision.
   * @param \GergelyRozsas\CloverDiff\Node\Revision\NodeRevisionInterface $revision_2
   *   The second node revision.
   *
   * @return float|null
   *   A float value if there is a valid difference, NULL otherwise.
   */
  public static function getPercentageDiff(NodeRevisionInterface $revision_1, NodeRevisionInterface $revision_2): ?float {
    $percentage_1 = self::getPercentage($revision_1);
    if (NULL === $percentage_1) {
      return NULL;
    }

    $percentage_2 = self::getPercentage($revision_2);
    if (NULL === $percentage_2) {
      return $percentage_1;
    }

    return $percentage_1 - $percentage_2;
  }

  private static function doGetPercentage(?int $a, ?int $b): ?float {
    if ((NULL === $a) || !$b) {
      return NULL;
    }
    return 100 * $a / $b;
  }

}
