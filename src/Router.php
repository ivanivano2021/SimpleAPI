<?php
namespace API;

class Router {
  protected $app = null;

  public function __construct($app) {
    $this->app = $app;
  }

  // Это стоит из БД подтягивать, но хорошая реализация менеджера предзагрузки - долгая задача
  protected $config = [
    'defaultResponseType' => 'json',
    'notFound' => [
      'controller' => 'NotFound'
    ],
    'routes' => [
      'table' => [
        'controller' => 'Table'
      ],
      'session_subscribe' => [
        'controller' => 'SessionSubscribe'
      ]
    ]
  ];

  public function find() {
    $url = $this->app->server->getRequestURL();

    preg_match('/\/api\/([a-z_]+)/i', $url, $matches);
    $match = new RouterMatch($this->config['defaultResponseType']);

    if (!isset($matches[1]) || !isset($this->config['routes'][$matches[1]])) {
      $match->setRoute($this->config['notFound']);
    } else {
      $match->setRoute($this->config['routes'][$matches[1]]);
    }
    
    return $match;
  }

  public function execute() {
    //var_dump($this->match);
  }
}