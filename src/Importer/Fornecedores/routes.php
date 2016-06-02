<?php

/**
 * Tabela de Apoio
 */
$app->get('/Importer/Fornecedores', function () use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->importFornecedores();
});


/**
 * Status Mensal
 */
$app->get('/Importer/Fornecedores/Status/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->importFornecedoresStatus($mes, $ano, $int);
});

/**
 * Status Anual
 */
$app->get('/Importer/Fornecedores/Status/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    for($x=1;$x<=12;$x++){
       $class->importFornecedoresStatus($x, $ano, $int);
    }
});