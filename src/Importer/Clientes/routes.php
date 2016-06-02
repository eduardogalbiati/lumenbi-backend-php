<?php

/**
 * Tabela de Apoio
 */
$app->get('/Importer/Clientes/AtualizaDesde/{datainicio}', function ($datainicio) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->importClientes($datainicio);
});

/**
 * Tabela de Apoio
 */
$app->get('/Importer/Clientes', function () use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->importClientes('1900-01-01');
});

/**
 * Status Mensal
 */
$app->get('/Importer/Clientes/Status/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->importClientesStatus($mes, $ano, $int);
});

/**
 * Status Anual
 */
$app->get('/Importer/Clientes/Status/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    for($x=1;$x<=12;$x++){
       $class->importClientesStatus($x, $ano, $int);
    }
});