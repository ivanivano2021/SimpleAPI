<?php
include 'autoloader.php';
include 'ErrorHandler.php';

// Нет в PHP правильной обработки путей по типу path.join(__dirname, 'src'). Печаль
$srcDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

// Стандартный PSR-4 загрузчик
$autoloader = new \Autoloader\Psr4Autoloader();
$autoloader->register();
$autoloader->addNamespace('\API', $srcDir);

// Добавим сюда и перехватчики ошибок, что не очень верно, ну да ладно
$errorHandler = new \ErrorHandler();

set_error_handler([$errorHandler, 'handlePhpError']);
set_exception_handler([$errorHandler, 'handleException']);
register_shutdown_function([$errorHandler, 'handleFatalError']);

new \API\App($srcDir);