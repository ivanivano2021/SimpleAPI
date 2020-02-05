<?php
namespace API\Reply;

class View extends AbstractReply {
  protected $data = [];

  public function __construct($data) {
    $this->data = $data;
  }

  public function getData() {
    return $this->data;
  }
}