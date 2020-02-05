<?php
namespace API\Repository;

// Правильно, конечно, организовать entity-менеджер, этакий ORM, а с не слать сырые запросы
// Но это как-нибудь потом
// TODO: ?

class SessionSubscribe extends AbstractRepository {
  public function existsSessionAndUser($sessionId, $userEmail) {
    $exists = \API\App::$db->fetchAll("SELECT s.MaxParticipant, s.Participants, p.ID as pID, p.Email FROM `Session` s, `Participant` p WHERE s.ID = :session_id AND p.Email = :user_email", [
      'session_id' => $sessionId,
      'user_email' => $userEmail
    ]);

    if (count($exists) > 0) {
      return $exists[0];
    } else {
      return [];
    }
  }

  public function registerUserOnSession($sessionId, $packedIds) {
    // Тут надо позаботиться о согласованности, транзакции, но InnoDB, ORM, движки обеспечат ACID, так что пишем пока так
    return \API\APP::$db->query("UPDATE `Session` SET `Participants` = :participants WHERE `ID` = :session_id", [
      'participants' => $packedIds,
      'session_id' => $sessionId
    ]);
  }
}