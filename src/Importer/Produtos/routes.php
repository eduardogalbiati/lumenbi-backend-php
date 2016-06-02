<?php

/**
 * Tabela de Apoio
 */
$app->get('/Importer/Produtos', function () use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
   // echo 'asd';die;
    return $class->importProdutos();
});

/**
 * Tabela de Apoio
 */
$app->get('/Importer/Materias', function () use ($app) {
    $class = new Auper\Materias\Controller\MateriasController($app);
    return $class->importMaterias();
});
    
/**
 * Tabela de Apoio
 */
$app->get('/Importer/Produtos/AtualizaDesde/{datainicio}', function ($datainicio) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->importProdutos($datainicio);
});


/**
 * Status Mensal
 */
$app->get('/Importer/Produtos/Status/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->importProdutosStatus($mes, $ano, $int);
});

/**
 * Status Anual
 */
$app->get('/Importer/Produtos/Status/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    for($x=1;$x<=12;$x++){
       $class->importProdutosStatus($x, $ano, $int);
    }
});