<?php
namespace API;

class Dispatcher {
  /**
   * @var \API\App
   */
  private $app;

  public function __construct($app) {
    $this->app = $app;
  }

  public function execute($routeMatch) {
    $abstractControllerName = '\API\Controller\AbstractController';
    $abstractReplyName = '\API\Reply\AbstractReply';

    $controllerName = sprintf("\API\Controller\\%s", $routeMatch->getController());
    $actionName = $routeMatch->getAction() ? sprintf('action%s', $routeMatch->getAction()) : 'actionIndex';

    // Тут можно было бы по циклу бегать, так реализовать внутренее перенаправление, ну да ладно
    if (class_exists($controllerName)) {
      $controller = new $controllerName($this->app);
    } else {
      throw new \Exception(sprintf("Cannot load the '%s' controller!", $controllerName));
    }

    if (!is_subclass_of($controller, $abstractControllerName)) {
      throw new \Exception(sprintf("All controllers must implement the One Ring: '%s', but the '%s' controller does not!", $abstractControllerName, $controllerName));
    }
    
    if (!method_exists($controller, $actionName)) {
      throw new \Exception(sprintf("The action '%s' does not exists in the '%s' controller!", $actionName, $controllerName));
    }

    $reply = $controller->$actionName();

    if (!is_object($reply) || !is_subclass_of($reply, $abstractReplyName)) {
      throw new \Exception(sprintf("The action '%s:%s' must return the '%s' object!", $controllerName, $actionName, $abstractReplyName));
    }

    return $reply;
  }
}