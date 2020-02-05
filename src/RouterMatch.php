<?php
namespace API;

class RouterMatch {
  /**
   * @var string
   */
  public $defaultResponseType;

  /**
   * @var array<string>
   */
  private $allowedResponseTypes = ['json'];

  /**
   * @var string
   */
  private $controller = null;

  /**
   * @var string|null
   */
  private $action = null;

  public function __construct($defaultResponseType) {
    $defaultResponseType = strtolower($defaultResponseType);

    if (in_array($defaultResponseType, $this->allowedResponseTypes)) {
      $this->defaultResponseType = $defaultResponseType;
    } else {
      // Тут надо бить тревогу, но просто поставим json
      $this->defaultResponseType = 'json';
    }
  }

  public function setRoute($routeData) {
    if (!isset($routeData['controller'])) {
      throw new \Exception(sprintf('The route data object has not contains the controller property'));
    }

    $this->controller = $routeData['controller'];

    if (isset($routeData['action'])) {
      $this->action = $routeData['action'];
    }
  }

  public function getController() {
    return $this->controller;
  }

  public function getAction() {
    return $this->action;
  }

  public function getResponseType() {
    return $this->defaultResponseType;
  }
}