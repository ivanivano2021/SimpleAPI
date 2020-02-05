<?php
namespace API;

class App {
  static public $srcPath = '';
  /**
   * @var \API\Router
   */
  public $router = null;

  /**
   * @var \API\Server
   */
  public $server = null;

  /**
   * @var \API\Filterer
   */
  public $filterer = null;

  /**
   * @var \API\Db
   */
  static public $db = null;

  public function __construct($srcPath) {
    self::$srcPath = $srcPath;

    $this->standardizeEnv();
    $this->run();
  }

  protected function standardizeEnv() {
    // Настройка единого окружения
    $this->server = new \API\Server();
    $this->filterer = new \API\Filterer();
    self::$db = new \API\Db();
  }

  protected function run() {
    $routeMatch = $this->route();
    $reply = $this->dispatch($routeMatch);
    $renderer = $this->getRenderer($reply, $routeMatch);

    $renderer->render($reply);
  }

  protected function route() {
    $this->router = new \API\Router($this);
    return $this->router->find();
  }

  protected function dispatch($routeMatch) {
    $dispatcher = new \API\Dispatcher($this);
    return $dispatcher->execute($routeMatch);
  }

  protected function getRenderer($reply, $routeMatch) {
    $renderer = new \API\Renderer();

    return $renderer->getRenderer($reply, $routeMatch);
  }
}