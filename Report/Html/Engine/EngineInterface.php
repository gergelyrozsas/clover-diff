<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Engine;

interface EngineInterface {

  /**
   * Renders a template.
   *
   * @param string $name
   *   The name of the template to be rendered.
   * @param array $variables
   *   The variables passed to the template to be rendered.
   *
   * @return string
   *   The rendered template.
   *
   * @throws \GergelyRozsas\CloverDiff\Report\Html\Engine\Exception\MissingTemplateException
   */
  public function render(string $name, array $variables = []): string;

}
