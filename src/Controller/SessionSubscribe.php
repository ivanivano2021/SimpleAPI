<?php
namespace API\Controller;

class SessionSubscribe extends \API\Controller\AbstractController {
  public function actionIndex() {
    if (!$this->isPost()) {
      return $this->error("This API requires a POST request!");
    }

    $userEmail = $this->filter('userEmail', 'str');
    $sessionId = $this->filter('sessionId', 'uint');

    $repo = new \API\Repository\SessionSubscribe();

    $sessionData = $repo->existsSessionAndUser($sessionId, $userEmail);

    if (count($sessionData) === 0) {
      return $this->error("Such an user or a session not found!");
    }

    $participants = $sessionData['Participants'];

    if (!$participants) {
      $ids = [];
    } else {
      $ids = explode(',', $sessionData['Participants']);
    }
    $userId = $sessionData['pID'];

    if (in_array($userId, $ids)) {
      return $this->message("You are already registered");
    }
    
    if (count($ids) === $sessionData['MaxParticipant']) {
      return $this->message("All places are occupied");
    }

    $ids[] = $userId;
    $packedIds = join(',', $ids);

    if ($repo->registerUserOnSession($sessionId, $packedIds)) {
      return $this->message("Congratulations, you are registered!");
    } else {
      return $this->message("Ooops, sorry, something went wrong.");
    }
  }
}