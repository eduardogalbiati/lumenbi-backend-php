<?php


/* --------------------------Estoque----------------------*/

// - Curva ABC
$app->get('/Estoque/CurvaAbc', function () use ($app) {
    $class = new Auper\Estoque\Controller\EstoqueController($app);
    return $class->getAbc();
});

$app->get('/Estoque/CurvaAbcSemQtd', function () use ($app) {
    $class = new Auper\Estoque\Controller\EstoqueController($app);
    return $class->getAbcSemQtd();
});
/*
//http://auper.local/Vendas/Valores/Anual
$app->get('/Vendas/Valores/Anual', function () use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getResumoVendasAnual();
});

//http://auper.local/Vendas/Valores/Mensal/Ano/2015
$app->get('/Vendas/Valores/Mensal/Ano/{ano}', function ($ano) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getResumoVendasMensal($ano);
});

//http://auper.local/Vendas/Valores/Diario/Mes/01/Ano/2015
$app->get('/Vendas/Valores/Diario/Mes/{mes}/Ano/{ano}', function ($mes, $ano) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getResumoVendasDiario($mes, $ano);
});

// Periodo Variável
$app->get('/Vendas/Valores/Anual/Periodo/{datainicial}/Ate/{datafinal}', function ($datainicial, $datafinal) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getResumoVendasPeriodo($datainicial, $datafinal,'Anual');
});
$app->get('/Vendas/Valores/Mensal/Periodo/{datainicial}/Ate/{datafinal}', function ($datainicial, $datafinal) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getResumoVendasPeriodo($datainicial, $datafinal,'Mensal');
});
$app->get('/Vendas/Valores/Diario/Periodo/{datainicial}/Ate/{datafinal}', function ($datainicial, $datafinal) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getResumoVendasPeriodo($datainicial, $datafinal, 'Diario');
});



//Vendas dos clientes
$app->get('/Vendas/Clientes/Valores', function () use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getTopClientesTotal();
});

//http://auper.local/Vendas/Valores/Anual
$app->get('/Vendas/Clientes/Valores/Ano/{ano}', function ($ano) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getTopClientesAno($ano);
});

//http://auper.local/Vendas/Valores/Mensal/Ano/2015
$app->get('/Vendas/Clientes/Valores/Mes/{mes}/Ano/{ano}', function ($ano, $mes) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getTopClientesMes($mes, $ano);
});



//---------------- Vendedores
$app->get('/Vendas/Vendedores/Valores', function () use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getTopVendedoresTotal();
});

//http://auper.local/Vendas/Valores/Anual
$app->get('/Vendas/Vendedores/Valores/Ano/{ano}', function ($ano) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getTopVendedoresAno($ano);
});

//http://auper.local/Vendas/Valores/Mensal/Ano/2015
$app->get('/Vendas/Vendedores/Valores/Mes/{mes}/Ano/{ano}', function ($ano, $mes) use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->getTopVendedoresMes($mes, $ano);
});

*/