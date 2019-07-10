<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Node\Revision;

use GergelyRozsas\CloverDiff\Node\NodeInterface;

interface NodeRevisionInterface extends NodeInterface {

  /**
   * Gets the id of the revision.
   *
   * @return int
   */
  public function getRevisionId(): int;

}
