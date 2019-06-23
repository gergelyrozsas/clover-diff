<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Tests;

use GergelyRozsas\CloverDiff\Version;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GergelyRozsas\CloverDiff\Version
 */
class VersionTest extends TestCase {

  public function testId() {
    $this->assertRegExp('/^[\d]+\.[\d]+\.[\d]+$/', Version::id());
  }

}
