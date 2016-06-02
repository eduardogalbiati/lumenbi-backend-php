<?php
set_time_limit(20000);
ini_set('memory_limit',('1024M') );
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\TranslationServiceProvider;
use Silex\Application;

$app = new Application();

require_once dirname(__DIR__).'/src/config.php';
require_once dirname(__DIR__).'/src/services.php';

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});


$app->get('/Importer/Faturamento', function () use ($app) {
    $class = new Auper\Faturamento\Controller\FaturamentoController($app);
    return $class->importResumoMensal();
});


require_once dirname(__DIR__).'/src/Auper/Clientes/routes.php';
require_once dirname(__DIR__).'/src/Auper/Vendas/routes.php';
require_once dirname(__DIR__).'/src/Auper/Produtos/routes.php';
require_once dirname(__DIR__).'/src/Auper/Fornecedores/routes.php';
require_once dirname(__DIR__).'/src/Auper/Vendedores/routes.php';
//require_once dirname(__DIR__).'/src/Auper/Compras/routes.php';
require_once dirname(__DIR__).'/src/Auper/CurvaAbc/routes.php';
require_once dirname(__DIR__).'/src/Auper/Estoque/routes.php';

require_once dirname(__DIR__).'/src/Importer/Clientes/routes.php';
require_once dirname(__DIR__).'/src/Importer/Vendas/routes.php';
require_once dirname(__DIR__).'/src/Importer/Produtos/routes.php';
require_once dirname(__DIR__).'/src/Importer/Fornecedores/routes.php';
require_once dirname(__DIR__).'/src/Importer/Vendedores/routes.php';
require_once dirname(__DIR__).'/src/Importer/Compras/routes.php';
require_once dirname(__DIR__).'/src/Importer/CurvaAbc/routes.php';
require_once dirname(__DIR__).'/src/Importer/Estoque/routes.php';


require_once dirname(__DIR__).'/src/LumenBiCore/Importers/routes.php';

$app->run();
return $app;
