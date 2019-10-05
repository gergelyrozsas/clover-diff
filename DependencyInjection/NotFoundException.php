<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\DependencyInjection;

use Psr\Container\NotFoundExceptionInterface;

/**
 * @codeCoverageIgnore
 */
class NotFoundException extends \RuntimeException implements NotFoundExceptionInterface {

}
