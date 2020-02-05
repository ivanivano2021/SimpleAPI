<?php
namespace API\Repository;

// Правильно, конечно, организовать entity-менеджер, этакий ORM, а с не слать сырые запросы
// Но это как-нибудь потом
// TODO: ?

class Table extends AbstractRepository {
  public function getTableRecords($tableName, $id = null) {
    $where = $id ? sprintf("WHERE `%s`.`ID` = %u", $tableName, $id) : ''; // Аж глаза вытекают :) TODO: Fix it

    $query = sprintf("SELECT * FROM `%s` %s", $tableName, $where);

    return \API\App::$db->fetchAll($query);
  }

  public function getSpeakers($ids) {
    return \API\App::$db->fetchAll(sprintf("SELECT * FROM `Speaker` WHERE `ID` IN (%s)", join(',', $ids)));
  }
}