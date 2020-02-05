<?php
namespace API\Reply;

class Error extends AbstractReply {
  protected $message = '';

  public function __construct($message) {
    $this->message = $message;
  }

  public function getMessage() {
    return $this->message;
  }
}