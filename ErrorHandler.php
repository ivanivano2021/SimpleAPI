<?php
class ErrorHandler {
  public static function handleException($e) {
    //var_dump($e);exit;
    printf("<pre>Oops, we had an exception:\n\n<br /><br />%s [%s] | %s:%s", $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
  }

  public static function handlePhpError($errorType, $errorString, $file, $line) {
    $message = sprintf("PHP Error: %s [%s] | %s:%s", $errorString, $errorType, $file, $line);

    // Выводим фатальные сообщения в OUTPUT
    // self::logException($message);
    printf("<pre>Oops, we had a big problem:\n\n<br /><br />%s", $message);
  }

  /**
   * Технически, это не перехватчик ошибок, а обработчик прекращения обработки PHP, но только тут можно отловить фатальные ошибки
   */
  public static function handleFatalError() {
    $error = @error_get_last();
    // Завершились удачно, выходим
    if (!$error) {
      return;
    }

    // Если тип не фатален, не пишем (для этого остальные обработчики)
    if (empty($error['type']) || !($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR))) {
      return;
    }

    try {
      $message = sprintf("Fatal Error: %s [%s] | %s:%s", $error['message'], $error['type'], $error['file'], $error['line']);

      // Выводим фатальные сообщения в OUTPUT
      // self::logException($message);
      printf("Oops, we had a big problem:\n\n<br /><br />%s", $message);
    } catch (Exception $e) {}
  }
}