<?php declare(strict_types=1);

namespace GergelyRozsas\CloverDiff\DependencyInjection;

use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CollectionNormalizerResolver;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\CommonClassBasedCollectionNormalizer;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\DelegatingCollectionNormalizer;
use GergelyRozsas\CloverDiff\Clover\Collection\Normalizer\EquivalentRootDirectoryBasedCollectionNormalizer;
use GergelyRozsas\CloverDiff\Clover\Parser;
use GergelyRozsas\CloverDiff\CloverDiff;
use GergelyRozsas\CloverDiff\Report\Html;
use GergelyRozsas\CloverDiff\Report\Html\Engine\PhpEngine;
use GergelyRozsas\CloverDiff\Report\Html\Renderer\BreadcrumbRenderer;
use GergelyRozsas\CloverDiff\Report\Html\Renderer\CoverageBarRenderer;
use GergelyRozsas\CloverDiff\Report\Html\Renderer\DiffBarRenderer;
use GergelyRozsas\CloverDiff\Report\Html\Renderer\DirectoryItemRenderer;
use GergelyRozsas\CloverDiff\Report\Html\Renderer\DirectoryRenderer;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @codeCoverageIgnore
 *
 * @internal
 */
class Container implements ContainerInterface {

  /**
   * @var object[]
   */
  protected $services;

  /**
   * @var array
   */
  protected $parameters = [
    'default_engine_class' => PhpEngine::class,
    'default_normalizer_class' => DelegatingCollectionNormalizer::class,
  ];

  /**
   * {@inheritdoc}
   */
  public function get($class) {
    return $this->services[$class] = $this->services[$class] ?? $this->doGet($class);
  }

  /**
   * {@inheritdoc}
   */
  public function has($class) {
    throw new \LogicException('This container contains a predefined set of services the caller code should be aware of.');
  }

  protected function getParameter(string $name) {
    if (!isset($this->parameters[$name])) {
      throw new NotFoundException("Parameter '{$name}' was not found.");
    }
    return $this->parameters[$name];
  }

  protected function doGet(string $class): object {
    switch ($class) {
      case BreadcrumbRenderer::class:
        return new BreadcrumbRenderer(
          $this->get($this->getParameter('default_engine_class'))
        );
      case CloverDiff::class:
        return new CloverDiff(
          $this->get(Parser::class),
          $this->get($this->getParameter('default_normalizer_class'))
        );
      case CollectionNormalizerResolver::class:
        $resolver = new CollectionNormalizerResolver();
        $resolver->addNormalizer($this->get(EquivalentRootDirectoryBasedCollectionNormalizer::class), 256);
        $resolver->addNormalizer($this->get(CommonClassBasedCollectionNormalizer::class), 128);
        return $resolver;
      case Container::class:
        return $this;
      case CoverageBarRenderer::class:
        return new CoverageBarRenderer(
          $this->get($this->getParameter('default_engine_class'))
        );
      case DelegatingCollectionNormalizer::class:
        return new DelegatingCollectionNormalizer(
          $this->get(CollectionNormalizerResolver::class)
        );
      case DiffBarRenderer::class:
        return new DiffBarRenderer(
          $this->get($this->getParameter('default_engine_class'))
        );
      case DirectoryItemRenderer::class:
        return new DirectoryItemRenderer(
          $this->get($this->getParameter('default_engine_class')),
          $this->get(CoverageBarRenderer::class),
          $this->get(DiffBarRenderer::class)
        );
      case DirectoryRenderer::class:
        return new DirectoryRenderer(
          $this->get($this->getParameter('default_engine_class')),
          $this->get(BreadcrumbRenderer::class),
          $this->get(DirectoryItemRenderer::class)
        );
      case Html::class:
        return new Html(
          $this->get(DirectoryRenderer::class),
          $this->get(Filesystem::class)
        );
      case CommonClassBasedCollectionNormalizer::class:
      case EquivalentRootDirectoryBasedCollectionNormalizer::class:
      case Filesystem::class:
      case PhpEngine::class:
      case Parser::class:
        return new $class();
      default:
        throw new NotFoundException("Service '{$class}' was not found.");
    }
  }

}
