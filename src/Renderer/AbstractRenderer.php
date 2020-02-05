<?php
namespace API\Renderer;

abstract class AbstractRenderer {
  protected $defaultHeaders = [];

  protected $contentTypeHeaders = [
    'json' => 'application/json'
  ];

  protected $responseType = 'json';


  abstract public function renderError(\API\Reply\Error $reply);
  abstract public function renderMessage(\API\Reply\Message $reply);
  abstract public function renderView(\API\Reply\View $reply);


  public function preRender() {
    $this->sendHeaders(); // TODO: Fix this shit
  }

  public function setResponseType($responseType) {
    $this->responseType = $responseType;
  }

  protected function sendHeaders($headers = []) {
    header(sprintf('Content-Type: %s', $this->responseType));

    $headers = array_merge($this->defaultHeaders, $headers);

    foreach ($headers as $key => $value) {
      header(sprintf("%s: %s", $key, $value));
    }
  }
}