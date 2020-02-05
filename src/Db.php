<?php
namespace API;

// Я, пожалуй, не буду писать адаптеры и прокидывать километры кода ради абстракций в демке
// Пусть будет PDO (который сам здоровая такая абстракция, хех) к MySQL
class Db {
  protected $pdo = null;

  public function __construct() {
    $config = $this->loadConfig();

    $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", $config['host'], $config['db_name'], $config['charset']);
    $options = [
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      \PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
      $this->pdo = new \PDO($dsn, $config['user'], $config['passwd'], $options);
    } catch (\PDOException $e) {
      throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
  }

  protected function loadConfig() { // Тут тоже стоило бы к хранилищу обращаться, но что не сделаешь во имя простоты?
    $pathToFile = __DIR__ . DIRECTORY_SEPARATOR . 'db.config.json';

    if (!file_exists($pathToFile)) {
      throw new \Exception("The db.config.json not found!");
    }

    try {
      $config = json_decode(file_get_contents($pathToFile), true);
    } catch (\Exception $e) {
      throw new \Exception("The db.config.json is broken!");
    }

    return $config;
  }

  public function query($query, $data = []) {
    $stmt = $this->pdo->prepare($query);

    if (!$stmt) return false;

    return $stmt->execute($data);
  }

  public function fetchAll($query, $data = []) {
    $stmt = $this->pdo->prepare($query);

    if (!$stmt) return [];
    
    $stmt->execute($data);

    return $stmt->fetchAll();
  }
}