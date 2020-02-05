<?php
namespace API;

class Renderer {
  protected $abstractRendererName = '\API\Renderer\AbstractRenderer';

  /**
   * @var \API\Renderer\AbstractRenderer
   */
  protected $renderer = null;

  public function getRenderer($reply, $routeMatch) {
    $rendererType = $routeMatch->getResponseType();

    switch ($rendererType) {
      case 'json':
        $renderer = new \API\Renderer\JSON();
        break;
      default:
        throw new \Exception(sprintf("Unknown type of renderer! Given: %s", $rendererType));
    }

    $renderer->setResponseType($rendererType);

    if (!is_subclass_of($renderer, $this->abstractRendererName)) {
      throw new \Exception(sprintf("All renderers must implement the One Ring: '%s', but the '%s' renderer does not!", $this->abstractRendererName, get_class($renderer)));
    }

    $this->renderer = $renderer;

    return $this;
  }

  public function render($reply) {
    $this->renderer->preRender();
    
    if ($reply instanceof \API\Reply\Error) {
      return $this->renderer->renderError($reply);
    } else if ($reply instanceof \API\Reply\Message) {
      return $this->renderer->renderMessage($reply);
    } else if ($reply instanceof \API\Reply\View) {
      return $this->renderer->renderView($reply);
    }

    throw new \Exception(sprintf("Unknown type of reply: %s", get_class($reply)));
  }
}