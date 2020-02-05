<?php
namespace API\Reply;

class Message extends AbstractReply {
  protected $message = '';

  public function __construct($message) {
    $this->message = $message;
  }

  public function getMessage() {
    return $this->message;
  }
}