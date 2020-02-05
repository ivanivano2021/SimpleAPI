<?php
namespace API\Controller;

class NotFound extends \API\Controller\AbstractController {
  public function actionIndex() {
    if (!$this->isPost()) {
      return $this->error("This API requires a POST request!");
    }
    
    return $this->error("Such method not found");
  }
}