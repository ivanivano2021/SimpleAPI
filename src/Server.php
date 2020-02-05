<?php
namespace API;

class Server {
  public function getRequestURL() {
    return $_SERVER['REQUEST_URI'];
  }

  public function isPost() {
    return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
  }

  public function get($key) {
    if (isset($_GET[$key])) {
      return $_GET[$key];
    } else if (isset($_POST[$key])) {
      return $_POST[$key];
    } else if (isset($_SERVER[$key])) {
      return $_SERVER[$key];
    } else if (isset($_SESSION[$key])) {
      return $_SESSION[$key];
    }

    return null;
  }
}