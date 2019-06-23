<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\Report\Html\Engine;

use GergelyRozsas\CloverDiff\Report\Html\Engine\Exception\MissingTemplateException;

class PhpEngine implements EngineInterface {

  /**
   * @var string
   */
  private $templateDirectory;

  public function __construct(
    string $template_directory = __DIR__ . '/../../../Resources'
  ) {
    $this->templateDirectory = $template_directory;
  }

  /**
   * {@inheritdoc}
   */
  public function render(string $name, array $variables = []): string {
    $template_path = $this->resolveTemplatePath($name);
    if (!file_exists($template_path)) {
      throw new MissingTemplateException($name);
    }

    return $this->doRender($template_path, $variables);
  }

  protected function resolveTemplatePath(string $name): string {
    return "{$this->templateDirectory}/{$name}.php";
  }

  private function doRender(string $template_path, array $context): string {
    extract($context);
    ob_start();
    include $template_path;
    return ob_get_clean();
  }

}
