<?php
/**
 * PhpUnit Tests
 *
 * The global configuration for the tests.
 */
if(! defined("ROOT")):
    define("ROOT", (__DIR__));
endif;

error_reporting(E_ALL | E_STRICT);

ini_set('display_errors', 1);

date_default_timezone_set('America/Sao_Paulo');

include_once 'vendor/autoload.php';
/**
 * crée une application et configure celle-ci.
 * @return Silex\Application
 */
function createApplication() {
    //putenv("MARKME_DB_DRIVER=pdo_sqlite");
   // $schema = file_get_contents(ROOT . '/Database/schema.sqlite.sql');
    $app = require  ROOT.'/web/index.php';
    $app["debug"] = true;
    //$app["exception_handler"]->disable();
    //$app["session.test"] = true;
    //$app["db"]->exec($schema);
    return $app;
}
