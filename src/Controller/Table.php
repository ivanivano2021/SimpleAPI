<?php
namespace API\Controller;

class Table extends \API\Controller\AbstractController {
  protected $allowedTables = ['News', 'Session']; // TODO: В конструкторе можно подтягивать из базы
  protected $requireExtends = ['Session'];

  public function actionIndex() {
    if (!$this->isPost()) {
      return $this->error("This API requires a POST request!");
    }

    $tableName = $this->filter('table', 'str', '%UNKNOWN_TABLE%');
    $id = $this->filter('id', 'uint');

    $repo = new \API\Repository\Table();

    $lowerTableName = strtolower($tableName);
  
    $found = array_search($lowerTableName, array_map('strtolower', $this->allowedTables)); // TODO: Fix it

    if ($found === false) {
      return $this->error(sprintf("A table '%s' not found", $tableName));
    } else {
      $tableName = $this->allowedTables[$found];
    }

    $data = $repo->getTableRecords($tableName, $id);

    $isNeedExtends = array_search($lowerTableName, array_map('strtolower', $this->requireExtends)); // TODO: Fix it

    if ($isNeedExtends !== false) {
      $method = sprintf("extendsTable%s", $this->requireExtends[$isNeedExtends]);

      if (method_exists($this, $method)) {
        $this->$method($data);
      }
    }

    return $this->view($data);
  }

  protected function extendsTableSession(&$data) { // TODO: Когда запилю ORM, добавить запрос, а не посылать второй
    $repo = new \API\Repository\Table();
    $ids = [];
    $idsMap = [];

    foreach ($data as $key => $row) {
      $_ids = explode(',', $row['Speakers']);
      $ids = array_merge($ids, $_ids);
      $idsMap[$key] = $_ids;
    }

    $ids = array_unique($ids);

    $speakers = $repo->getSpeakers($ids);
    
    foreach ($idsMap as $key => $ids) { // TODO: Сделать хорошо
      $currentSpeakers = [];

      foreach ($speakers as $speaker) {
        if (in_array($speaker['ID'], $ids)) {
          $currentSpeakers[] = $speaker;
        }
      }

      $data[$key]['Speakers'] = $currentSpeakers;
    }
  }
}