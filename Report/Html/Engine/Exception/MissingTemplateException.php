<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Engine\Exception;

/**
 * @codeCoverageIgnore
 */
class MissingTemplateException extends \Exception {

  public function __construct(string $template_name) {
    parent::__construct(vsprintf("'%s' could not be found.", [
      $template_name,
    ]));
  }

}
