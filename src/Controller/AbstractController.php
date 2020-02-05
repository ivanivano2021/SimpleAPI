<?php
namespace API\Controller;

abstract class AbstractController {
  /**
   * @var \API\App
   */
  private $app = null;

  public function __construct($app) {
    $this->app = $app;
  }

  protected function view($data) {
    return new \API\Reply\View($data);
  }

  protected function error($message) {
    return new \API\Reply\Error($message);
  }

  protected function message($message) {
    return new \API\Reply\Message($message);
  }

  protected function db() {
    return $this->app->db;
  }

  protected function filter($key, $type, $fallback = null, $options = []) {
    $rawValue = $this->app->server->get($key);

    if ($rawValue) {
     return $this->app->filterer->filter($rawValue, $type, $options);
    } else {
      return $fallback;
    }
  }

  protected function isPost() {
    return $this->app->server->isPost();
  }
}