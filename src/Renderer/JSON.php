<?php
namespace API\Renderer;

class JSON extends AbstractRenderer {
  protected $data = [
    'status' => 'error',
    'payload' => []
  ];

  public function renderError(\API\Reply\Error $reply) {
    $this->data['message'] = $reply->getMessage();
    $this->flush();
  }

  public function renderMessage(\API\Reply\Message $reply) {
    $this->data['status'] = 'ok';
    $this->data['message'] = $reply->getMessage();
    $this->flush();
  }

  public function renderView(\API\Reply\View $reply) {
    $this->data['status'] = 'ok';
    $this->data['payload'] = $reply->getData();
    $this->flush();
  }

  protected function flush() {
    print json_encode($this->data);
  }
}